@extends('layouts.app')

@section('content')
@php
    $selectedKategori = request('kategori');
    $schema           = config('category_tables');
    $cols             = $schema[$selectedKategori] ?? $schema['default'];
@endphp

<div class="container py-4" style="max-width: 1800px;">
    {{-- Bildirimler --}}
    @if(session('success')) <div class="alert alert-success">{{ session('success') }}</div> @endif
    @if(session('error'))   <div class="alert alert-danger">{{ session('error') }}</div>   @endif

    <div class="d-flex align-items-center justify-content-between flex-wrap gap-2 mb-3">
        <h1 class="h4 fw-bold mb-0">📋 Başvuru Listesi</h1>
        <div class="d-flex gap-2">
            <span class="badge bg-primary-subtle text-primary-emphasis">Toplam: {{ number_format($students->total()) }}</span>
             <a href="{{ route('admin.students.basilan_kartlar') }}"
           class="badge text-decoration-none
                  bg-warning-subtle text-warning-emphasis
                  border border-warning-subtle"
           title="Basılacak kartlar sayfasına git">
            Basılacak kart: {{ $basilacakKartSayisi }}
        </a>
        </div>
    </div>
   

    {{-- Filtre --}}
    <form method="GET" action="{{ route('admin.students.index') }}" class="row g-2 align-items-end mb-3">
        <div class="col-auto">
            <label for="kategori" class="form-label mb-1">Kategori</label>
            <select name="kategori" id="kategori" class="form-select">
                <option value="">Tümü</option>
                @foreach(($kategoriler ?? []) as $k)
                    <option value="{{ $k }}" @selected(request('kategori') === $k)>{{ $k }}</option>
                @endforeach
            </select>
        </div>
        <div class="col-auto">
            <button type="submit" class="btn btn-primary">Filtrele</button>
            @if(request('kategori'))
                <a href="{{ route('admin.students.index') }}" class="btn btn-outline-secondary">Sıfırla</a>
            @endif
        </div>
    </form>

    <div class="card shadow-sm border-0 rounded-4 overflow-hidden">
        <div class="table-responsive">
            <table class="table align-middle mb-0 table-hover table-modern">
                <thead class="table-light sticky-top" style="top:0; z-index:3">
                    <tr class="text-secondary">
                        @foreach($cols as $col)
                            @php $label = array_key_first($col); @endphp
                            <th scope="col">{{ $label }}</th>
                        @endforeach
                        <th scope="col" class="text-end" style="min-width: 260px;">İşlemler</th>
                    </tr>
                </thead>
                <tbody>
                @forelse($students as $student)
                    @php
                        // Kategori slug (ör: "Öğrenci" -> "ogrenci")
                        $katSlug = \Illuminate\Support\Str::slug($student->kategori ?? 'diger');
                    @endphp
                    <tr class="row-cat-{{ $katSlug }}">
                        @foreach($cols as $col)
                            @php
                                $label = array_key_first($col);
                                $spec  = $col[$label];

                                $type = 'text'; $key = $spec; $fmt = null;
                                if (is_string($spec) && str_starts_with($spec, 'file:')) {
                                    $type = 'file';  $key = substr($spec, 5);
                                } elseif (is_string($spec) && str_starts_with($spec, 'badge:')) {
                                    $type = 'badge'; $key = substr($spec, 6);
                                } else {
                                    if (is_string($spec)) {
                                        $parts = explode('|', $spec, 2);
                                        $key   = $parts[0];
                                        $fmt   = $parts[1] ?? null; // örn: date
                                    }
                                }
                            @endphp

                            

                            @if($type === 'file')
                                @php $path = data_get($student, $key); @endphp
                                <td class="cell-nowrap">
                                    @if($path)
                                        <a href="{{ route('admin.students.download', ['id' => $student->id, 'file_type' => $key]) }}"
                                           class="btn btn-sm btn-soft btn-soft-info" title="Dosyayı indir">
                                           İndir
                                        </a>
                                    @else
                                        <span class="text-muted">Yok</span>
                                    @endif
                                </td>

                            @elseif($type === 'badge')
                                @php
                                    $val = data_get($student, $key);
                                    if ($key === 'aydinlatma_onay') {
                                        $txt   = $val ? 'Onaylı' : 'Yok';
                                        $klass = $val ? 'status-ok' : 'status-wait';
                                    } else {
                                        $txt = $val ?? 'İşlem Bekliyor';
                                        $klass = match($txt){
                                            'Eksik Belge' => 'status-missing',
                                            'Sicil Oluştu - Tahakkuk Girildi' => 'status-print',
                                            'Onaylandı' => 'status-ok',
                                            default => 'status-wait',
                                        };
                                    }
                                @endphp
                                <td><span class="status-pill {{ $klass }}">{{ $txt }}</span></td>

                            @else
                                @php
                                    $v = data_get($student, $key);
                                    if ($fmt === 'date' && !empty($v)) {
                                        try { $v = \Carbon\Carbon::parse($v)->format('d/m/Y'); } catch (\Exception $e) {}
                                    }
                                    $isLongText = in_array($key, ['adres','email','ad_soyad'], true);
                                @endphp
                                @if($key === 'kategori')
                                    <td>
                                        <span class="chip chip-{{ $katSlug }}">
                                            <span class="chip-dot"></span> {{ $v ?? '-' }}
                                        </span>
                                    </td>
                                @elseif($isLongText)
                                    <td class="truncate" title="{{ $v }}">{{ $v ?? '-' }}</td>
                                @else
                                    <td>{{ $v ?? '-' }}</td>
                                @endif
                            @endif
                        @endforeach

                        {{-- İşlemler --}}
                        <td class="text-end">
                            <div class="d-inline-flex flex-wrap gap-2 justify-content-end actions">
                                <a href="{{ route('admin.students.edit', $student) }}"
                                   class="btn btn-sm btn-soft btn-soft-primary" title="Kaydı düzenle">
                                   ✏️ Düzenle
                                </a>

                                <form action="{{ route('admin.students.update_status', $student->id) }}" method="POST" class="d-inline">
                                    @csrf
                                    <input type="hidden" name="durum" value="Onaylandı">
                                    <button type="submit" class="btn btn-sm btn-soft btn-soft-success" title="Onayla">
                                        ✅ Onayla
                                    </button>
                                </form>

                                <button type="button"
                                        class="btn btn-sm btn-soft btn-soft-danger"
                                        data-bs-toggle="modal"
                                        data-bs-target="#eksikBelgeModal{{ $student->id }}"
                                        title="Eksik belge talep et">
                                    ⚠️ Eksik Belge
                                </button>
                            </div>

                            {{-- Eksik Belge Modal --}}
                            <div class="modal fade" id="eksikBelgeModal{{ $student->id }}" tabindex="-1" aria-labelledby="eksikBelgeModalLabel{{ $student->id }}" aria-hidden="true">
                                <div class="modal-dialog modal-dialog-centered">
                                    <form action="{{ route('admin.students.send_eksik_belge', ['id' => $student->id]) }}" method="POST" class="modal-content rounded-4">
                                        @csrf
                                        <div class="modal-header border-0">
                                            <h5 class="modal-title" id="eksikBelgeModalLabel{{ $student->id }}">Eksik Belge Bildirimi</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Kapat"></button>
                                        </div>
                                        <div class="modal-body">
                                            <div class="mb-3">
                                                <label class="form-label" for="aciklama-{{ $student->id }}">Açıklama</label>
                                                <textarea id="aciklama-{{ $student->id }}" name="aciklama" class="form-control" rows="4" placeholder="Eksik belgeler..." required></textarea>
                                            </div>
                                            <div class="mb-2">
                                                <label class="form-label" for="durum-{{ $student->id }}">Durum</label>
                                                <select id="durum-{{ $student->id }}" name="durum" class="form-select">
                                                    <option value="Eksik Belge" selected>Eksik Belge</option>
                                                    <option value="İşlem Bekliyor">İşlem Bekliyor</option>
                                                    <option value="Sicil Oluştu - Tahakkuk Girildi">Sicil Oluştu - Tahakkuk Girildi</option>
                                                    <option value="Onaylandı">Onaylandı</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="modal-footer border-0">
                                            <button type="button" class="btn btn-outline-secondary rounded-pill" data-bs-dismiss="modal">İptal</button>
                                            <button type="submit" class="btn btn-danger rounded-pill">Gönder</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="{{ count($cols) + 1 }}" class="text-center py-5">
                            <div class="text-muted">Kayıt bulunamadı.</div>
                        </td>
                    </tr>
                @endforelse
                </tbody>
            </table>
        </div>

        {{-- Paginasyon --}}
        <div class="d-flex justify-content-center justify-content-md-end p-3 pagination-wrap">
            {{ $students->withQueryString()->links('pagination::bootstrap-5') }}
        </div>
    </div>
</div>

{{-- Stil bloğu (başlıklar siyah + kategori renkleri + modern dokunuşlar) --}}
<style>
  /* Başlıkları siyah yap */
  .table thead th{
    font-weight:700; letter-spacing:.02em; color:#000;
  }

  /* Modern tablo dokunuşları */
  .table-modern{ --bs-table-bg:transparent; }
  .table-modern thead th{ position:sticky; top:0; z-index:2; background:#f8fafc; border-bottom:1px solid rgba(0,0,0,.08)!important; white-space:nowrap; }
  .table-modern.table > :not(caption) > * > *{ padding-top:.7rem; padding-bottom:.7rem; vertical-align:middle; }
  .table-modern tbody tr + tr td{ border-top:1px dashed rgba(0,0,0,.05); }
  .table-modern tbody tr:hover{ background:rgba(0,0,0,.03); }
  .truncate{ max-width:420px; overflow:hidden; text-overflow:ellipsis; white-space:nowrap; }
  .cell-nowrap{ white-space:nowrap; }

  /* Soft butonlar */
  .btn-soft{
    --bg:rgba(0,0,0,.06); --fg:#0f172a;
    background:var(--bg); color:var(--fg); border:1px solid transparent;
    border-radius:999px; padding:.38rem .7rem; box-shadow:0 1px 0 rgba(0,0,0,.04);
    transition: transform .08s ease, filter .15s ease, box-shadow .15s ease;
  }
  .btn-soft:hover{ box-shadow:0 2px 6px rgba(0,0,0,.08); transform:translateY(-1px); filter:brightness(.97); }
  .btn-soft:active{ transform:translateY(0); }
  .btn-soft-primary{ --bg:rgba(37,99,235,.12); --fg:#1d4ed8; }
  .btn-soft-success{ --bg:rgba(22,163,74,.12); --fg:#15803d; }
  .btn-soft-danger { --bg:rgba(220,38,38,.12); --fg:#b91c1c; }
  .btn-soft-info   { --bg:rgba(14,165,233,.12); --fg:#0ea5e9; }
  .actions{ display:flex; gap:.4rem; }

  /* Durum pill */
  .status-pill{
    display:inline-flex; align-items:center; gap:.4rem;
    border-radius:999px; padding:.25rem .6rem; font-weight:600; font-size:.78rem;
    border:1px solid rgba(0,0,0,.06);
  }
  .status-pill::before{ content:''; width:.5rem; height:.5rem; border-radius:999px; background:currentColor; opacity:.45; }
  .status-wait   { background:#e5e7eb; color:#374151; }
  .status-missing{ background:#fee2e2; color:#b91c1c; }
  .status-print  { background:#fef3c7; color:#a16207; }
  .status-ok     { background:#dcfce7; color:#166534; }

  /* Kategori chip */
  .chip{
    display:inline-flex; align-items:center; gap:.4rem;
    padding:.3rem .65rem; border-radius:999px; font-size:.82rem;
    background:rgba(0,0,0,.06); color:#334155; border:1px solid rgba(0,0,0,.06);
    transition: transform .08s ease, filter .15s ease, box-shadow .15s ease;
  }
  .chip:hover { transform: translateY(-1px); filter: brightness(1.02); }
  .chip-dot{ width:.5rem; height:.5rem; border-radius:999px; background:currentColor; box-shadow:0 0 0 2px rgba(255,255,255,.7) inset; }

  /* === KATEGORİYE GÖRE SATIR RENKLERİ === */
  /* Arkaplanı çok açık tuttuk; okunabilirlik ve zebra hissi için hover yine çalışır. */
  .row-cat-ogrenci           { background: #eef6ff; } /* açık mavi */
  .row-cat-ogretmen          { background: #fff5eb; } /* açık turuncu */
  .row-cat-belediye          { background: #f8f9fa; } /* açık gri */
  .row-cat-emniyet           { background: #ebfffb; } /* su yeşili */
  .row-cat-jandarma          { background: #f5ebff; } /* açık mor */
  .row-cat-gazi              { background: #e9fff3; } /* açık yeşil */
  .row-cat-sehit             { background: #ffeef2; } /* açık pembe/kırmızı */
  .row-cat-65-yas-ustu       { background: #fffbea; } /* açık sarı */
  .row-cat-engelli           { background: #eef2ff; } /* çok açık mavi-mor */
  .row-cat-engelli-refakatci { background: #eafff3; } /* açık yeşil ton */
  .row-cat-posta             { background: #f3f4f6; } /* nötr gri */
  .row-cat-annekart          { background: #fff1e6; } /* pastel turuncu */
  .row-cat-sari-basin        { background: #fff9e6; } /* limon sarı */
  .row-cat-zabita            { background: #ecebff; } /* lila */

  /* Kategori chip’leriyle görsel uyum */
  .chip-ogrenci           { color:#2563eb; background:rgba(37,99,235,.12); }
  .chip-ogretmen          { color:#16a34a; background:rgba(22,163,74,.12); }
  .chip-belediye          { color:#ea580c; background:rgba(234,88,12,.12); }
  .chip-emniyet           { color:#0ea5e9; background:rgba(14,165,233,.12); }
  .chip-jandarma          { color:#7c3aed; background:rgba(124,58,237,.12); }
  .chip-gazi              { color:#059669; background:rgba(5,150,105,.12); }
  .chip-sehit             { color:#dc2626; background:rgba(220,38,38,.12); }
  .chip-65-yas-ustu       { color:#a16207; background:rgba(245,158,11,.18); }
  .chip-engelli           { color:#1d4ed8; background:rgba(29,78,216,.12); }
  .chip-engelli-refakatci { color:#0e7490; background:rgba(14,116,144,.12); }
  .chip-posta             { color:#475569; background:rgba(71,85,105,.14); }
  .chip-annekart          { color:#ea580c; background:rgba(234,88,12,.12); }
  .chip-sari-basin        { color:#a16207; background:rgba(245,158,11,.18); }
  .chip-zabita            { color:#7c3aed; background:rgba(124,58,237,.12); }

  /* Pagination (pill/soft) */
  .pagination-wrap{ display:flex; justify-content:center; }
  @media (min-width:768px){ .pagination-wrap{ justify-content:flex-end; } }
  .pagination .page-link{
      border-radius:999px!important; border:1px solid transparent;
      background:rgba(0,0,0,.06); color:#0f172a; padding:.45rem .8rem; line-height:1;
      box-shadow:0 1px 0 rgba(0,0,0,.04) inset; transition: transform .08s ease, filter .15s ease, box-shadow .15s ease;
  }
  .pagination .page-item .page-link:hover{ filter:brightness(.97); transform:translateY(-1px); }
  .pagination .page-item.active .page-link{
      background:rgba(37,99,235,.12); color:#1d4ed8; border-color:rgba(37,99,235,.25); box-shadow:0 2px 6px rgba(37,99,235,.18);
  }
  .pagination .page-item.disabled .page-link{ opacity:.55; cursor:not-allowed; }

  /* Dark mode */
  @media (prefers-color-scheme: dark){
    .table-modern thead th{ background:#0b1220; color:#fff; border-bottom-color:rgba(255,255,255,.12)!important; }
    .table-modern tbody tr:hover{ background:rgba(255,255,255,.06); }
    .pagination .page-link{ background: rgba(255,255,255,.08); color:darkblue; }
    .pagination .page-item.active .page-link{ background: rgba(37,99,235,.25); color:#fff; border-color: rgba(37,99,235,.4); }
  }
</style>
@endsection
