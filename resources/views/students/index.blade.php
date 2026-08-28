<!DOCTYPE html>
<html lang="tr">
<head>

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kırklareli Belediye Başkanlığı 39 Kent Kart - Başvuru Formu</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f3f7fb;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        .form-container {
            background-color: #03a0db;
            color: white;
            border-radius: 16px;
            box-shadow: 0 10px 28px rgba(3, 160, 219, 0.22);
            padding: 32px;
            margin-top: 30px;
        }
        .form-title { color: white; margin-bottom: 8px; font-weight: 700; text-align: center; }
        .form-control { border-radius: 10px; border: 1px solid #d7e6f0; padding: 10px 12px; font-size: 16px; }
        .form-control:focus { border-color: #0056b3; box-shadow: 0 0 0 3px rgba(255,255,255,.35); }
        .btn-primary { background-color: #0056b3; border: none; border-radius: 10px; padding: 12px 20px; font-size: 18px; font-weight: 600; transition: background-color .3s ease; }
        .btn-primary:hover { background-color: #004494; }
        footer { margin-top: 20px; font-size: 14px; text-align: center; color: #ffffff; }
        .form-label { font-weight: bold; }
        .text-center p { margin: 0; }
        .header-section { display: flex; align-items: center; justify-content: center; margin-bottom: 20px; }
        .header-section img { margin-right: 20px; width: 100px; filter: brightness(0) invert(1); }
        footer span { color: red; }
        .adimlar-kutu { background: rgba(255,255,255,.14); border: 1px solid rgba(255,255,255,.28); border-radius: 14px; padding: 18px; margin-bottom: 8px; }
        .adimlar-kutu h3 { font-size: 1.15rem; font-weight: 700; margin-bottom: 6px; }
        .adimlar-kutu p { margin-bottom: 14px; opacity: .95; }
        .adimlar-kutu .accordion-item { background: #fff; color: #12324a; border: none; border-radius: 10px !important; overflow: hidden; margin-bottom: 8px; }
        .adimlar-kutu .accordion-button { font-weight: 600; box-shadow: none; }
        .adimlar-kutu .accordion-button:not(.collapsed) { background: #e8f6fc; color: #0056b3; }
        .adimlar-kutu .accordion-body { font-size: 15px; line-height: 1.55; }
        .adim-no { display: inline-flex; align-items: center; justify-content: center; width: 26px; height: 26px; border-radius: 50%; background: #03a0db; color: #fff; font-size: 13px; margin-right: 8px; }
        .btn-basvuruya-gec { background: #fff; color: #0056b3; border: none; border-radius: 10px; padding: 12px 28px; font-size: 18px; font-weight: 700; }
        .btn-basvuruya-gec:hover { background: #e8f6fc; color: #004494; }
        .form-section-title { font-size: 15px; font-weight: 700; letter-spacing: .02em; margin: 8px 0 10px; padding-bottom: 6px; border-bottom: 1px solid rgba(255,255,255,.35); }
        .form-hint { font-size: 13px; opacity: .9; margin-top: 6px; font-weight: 400; }
        .alert { border-radius: 10px; }
    </style>
</head>
<body>
<div class="container mt-4">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="form-container">
                <div class="header-section">
                    <img src="{{ asset('belediye-logo.png') }}" alt="Belediye Logo">
                    <h2 class="form-title">Kırklareli Belediye Başkanlığı<br>39 Kent Kart<br>Başvuru Formu</h2>
                </div>

                {{-- Hata ve başarı banner'ları (dışarıda da dursun ki hep görünsün) --}}
                @if ($errors->any())
                    <div class="alert alert-danger">
                        <strong>Formda hatalar var:</strong>
                        <ul class="mb-0 mt-2">
                            @foreach ($errors->all() as $err)
                                <li>{{ $err }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif
                @if (session('success'))
                    <div class="alert alert-success">{{ session('success') }}</div>
                @endif

                @php $showBasvuruFormu = $errors->any() || session('success'); @endphp

                <div id="basvuru-adimlari" class="adimlar-kutu" @if($showBasvuruFormu) style="display:none;" @endif>
                    <h3>Öğrenciler için 5 adımda başvuru</h3>
                    <p>Başvuruya geçmeden önce aşağıdaki adımları açarak okuyunuz.</p>
                    <div class="accordion" id="ogrenciBasvuruAdimlari">
                        <div class="accordion-item">
                            <h2 class="accordion-header">
                                <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#adim1" aria-expanded="true" aria-controls="adim1">
                                    <span class="adim-no">1</span> Öğrenci belgenizi alın
                                </button>
                            </h2>
                            <div id="adim1" class="accordion-collapse collapse show" data-bs-parent="#ogrenciBasvuruAdimlari">
                                <div class="accordion-body">
                                    e-Devlet (turkiye.gov.tr) üzerinden güncel <strong>öğrenci belgenizi PDF</strong> olarak indirin. Belge, öğrenim gördüğünüz okulu ve bölümü göstermelidir.
                                </div>
                            </div>
                        </div>
                        <div class="accordion-item">
                            <h2 class="accordion-header">
                                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#adim2" aria-expanded="false" aria-controls="adim2">
                                    <span class="adim-no">2</span> Vesikalık fotoğrafınızı hazırlayın
                                </button>
                            </h2>
                            <div id="adim2" class="accordion-collapse collapse" data-bs-parent="#ogrenciBasvuruAdimlari">
                                <div class="accordion-body">
                                    Kartta kullanılacak vesikalık fotoğrafı hazırlayın. Fotoğraf yalnızca <strong>JPG veya JPEG</strong> formatında olmalıdır. Vesikalık dışında (selfie, manzara, tarama vb.) yüklenen görseller işleme alınmaz.
                                </div>
                            </div>
                        </div>
                        <div class="accordion-item">
                            <h2 class="accordion-header">
                                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#adim3" aria-expanded="false" aria-controls="adim3">
                                    <span class="adim-no">3</span> Kişisel bilgilerinizi doldurun
                                </button>
                            </h2>
                            <div id="adim3" class="accordion-collapse collapse" data-bs-parent="#ogrenciBasvuruAdimlari">
                                <div class="accordion-body">
                                    Ad soyad, T.C. kimlik no, baba adı, doğum tarihi, doğum yeri, telefon, e-posta, adres ve kazandığınız bölüm bilgilerini kimlikteki haliyle, eksiksiz girin.
                                </div>
                            </div>
                        </div>
                        <div class="accordion-item">
                            <h2 class="accordion-header">
                                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#adim4" aria-expanded="false" aria-controls="adim4">
                                    <span class="adim-no">4</span> Belgelerinizi yükleyin
                                </button>
                            </h2>
                            <div id="adim4" class="accordion-collapse collapse" data-bs-parent="#ogrenciBasvuruAdimlari">
                                <div class="accordion-body">
                                    Öğrenci belgesini <strong>PDF</strong>, vesikalık fotoğrafı <strong>JPG / JPEG</strong> olarak yükleyin. Dosyalarınızın okunaklı ve güncel olduğundan emin olun.
                                </div>
                            </div>
                        </div>
                        <div class="accordion-item">
                            <h2 class="accordion-header">
                                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#adim5" aria-expanded="false" aria-controls="adim5">
                                    <span class="adim-no">5</span> Onaylayın ve başvuruyu gönderin
                                </button>
                            </h2>
                            <div id="adim5" class="accordion-collapse collapse" data-bs-parent="#ogrenciBasvuruAdimlari">
                                <div class="accordion-body">
                                    Aydınlatma metnini okuyup onaylayın, ardından <strong>Başvuru Gönder</strong> butonuna tıklayın. Başvurunuz alındığında “İşlem Bekliyor” durumuna geçer.
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="text-center mt-3">
                        <button type="button" id="basvuruya-gec" class="btn btn-basvuruya-gec">Başvuruya Geç</button>
                    </div>
                </div>

                <form id="basvuru-formu" action="{{ route('students.store') }}" method="POST" enctype="multipart/form-data" @unless($showBasvuruFormu) style="display:none;" @endunless>
                    @csrf

                    <div class="mb-3">
                        <label for="kategori" class="form-label">Başvuru Kategorisi</label>
                        <select name="kategori" id="kategori" class="form-control" required>
                            <option value="">Seçiniz</option>
                            <option value="Ogrenci" {{ old('kategori', 'Ogrenci') === 'Ogrenci' ? 'selected' : '' }}>Öğrenci</option>
                            <option value="Ogretmen" {{ old('kategori') === 'Ogretmen' ? 'selected' : '' }}>Öğretmen</option>
                            <option value="Belediye" {{ old('kategori') === 'Belediye' ? 'selected' : '' }}>Belediye Personeli</option>
                            <option value="Emniyet" {{ old('kategori') === 'Emniyet' ? 'selected' : '' }}>Emniyet Hizmetleri</option>
                            <option value="Jandarma" {{ old('kategori') === 'Jandarma' ? 'selected' : '' }}>Jandarma Hizmetleri</option>
                            <option value="Gazi" {{ old('kategori') === 'Gazi' ? 'selected' : '' }}>Gazi veya Gazi Yakını</option>
                            <option value="Sehit" {{ old('kategori') === 'Sehit' ? 'selected' : '' }}>Şehit Yakını</option>
                            <option value="Engelli_Refakatci" {{ old('kategori') === 'Engelli_Refakatci' ? 'selected' : '' }}>Engelli ve Refakatçısı</option>
                            <option value="Posta" {{ old('kategori') === 'Posta' ? 'selected' : '' }}>Posta Dağıtıcıları</option>
                            {{-- <option value="Annekart">Annekart</option> --}}
                            <option value="Sari_Basin" {{ old('kategori') === 'Sari_Basin' ? 'selected' : '' }}>Sarı Basın Kartı</option>
                            <option value="Zabita" {{ old('kategori') === 'Zabita' ? 'selected' : '' }}>Belediye Zabıtası</option>
                        </select>
                    </div>

                    <div id="kategori-formu"><!-- AJAX ile buraya alanlar gelecek --></div>

                    <div class="form-check mb-3">
                        <input class="form-check-input" type="checkbox" id="aydinlatma_onay" name="aydinlatma_onay" value="1" required {{ old('aydinlatma_onay') ? 'checked' : '' }}>
                        <label class="form-check-label" for="aydinlatma_onay">
                            <a href="https://api.kirklarelibelediyesi.com/files/dokuman/kirklareli-kvkk.pdf" style="color:#1f2937;background: #fff; padding:2px 6px; border-radius:6px;">Aydınlatma metnini</a> okudum onaylıyorum.
                        </label>
                    </div>

                    <div class="text-center mt-4">
                        <button type="submit" class="btn btn-primary">Başvuru Gönder</button>
                    </div>
                </form>

                <footer>
                    <p>Kırklareli Belediye Başkanlığı Bilgi İşlem Müdürlüğü</p>
                </footer>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const kategoriSelect = document.getElementById('kategori');
    const formDiv = document.getElementById('kategori-formu');
    const adimlar = document.getElementById('basvuru-adimlari');
    const basvuruFormu = document.getElementById('basvuru-formu');
    const basvuruyaGec = document.getElementById('basvuruya-gec');
    const formUrlBase = @json(route('kategori.form', ['kategori' => '___K___']));

    function loadKategoriForm() {
        const kategori = kategoriSelect.value;
        if (!kategori) { formDiv.innerHTML = ''; return; }

        const url = formUrlBase.replace('___K___', encodeURIComponent(kategori));
        fetch(url)
            .then(async (r) => {
                const text = await r.text();
                if (!r.ok) throw new Error(text || ('HTTP ' + r.status));
                formDiv.innerHTML = text;
            })
            .catch(err => {
                formDiv.innerHTML = `<div class="alert alert-danger">Form yüklenemedi: ${err.message}</div>`;
            });
    }

    function showBasvuruFormu() {
        if (adimlar) adimlar.style.display = 'none';
        if (basvuruFormu) basvuruFormu.style.display = '';
        kategoriSelect.value = 'Ogrenci';
        loadKategoriForm();
        window.scrollTo({ top: 0, behavior: 'smooth' });
    }

    if (basvuruyaGec) {
        basvuruyaGec.addEventListener('click', showBasvuruFormu);
    }

    kategoriSelect.addEventListener('change', loadKategoriForm);

    formDiv.addEventListener('change', function (e) {
        const input = e.target;
        if (input.id !== 'vesikalik' || kategoriSelect.value !== 'Ogrenci' || !input.files || !input.files[0]) return;
        const name = (input.files[0].name || '').toLowerCase();
        if (!name.endsWith('.jpg') && !name.endsWith('.jpeg')) {
            input.value = '';
            alert('Vesikalık fotoğraf yalnızca JPG veya JPEG formatında yüklenebilir.');
        }
    });

    // Hata/başarı sonrası form zaten açıksa öğrenci formunu yükle
    if (basvuruFormu && basvuruFormu.style.display !== 'none') {
        if (!kategoriSelect.value) kategoriSelect.value = 'Ogrenci';
        loadKategoriForm();
    }
});
</script>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
