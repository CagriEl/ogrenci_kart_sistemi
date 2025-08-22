<!DOCTYPE html>
<html lang="tr">
<head>

    <script>
document.addEventListener('DOMContentLoaded', function () {
  const kategoriSelect = document.getElementById('kategori');
  const formDiv = document.getElementById('kategori-formu');

  kategoriSelect.addEventListener('change', loadKategoriForm);

  function loadKategoriForm() {
    const kategori = kategoriSelect.value;
    if (!kategori) { formDiv.innerHTML = ''; return; }

    // Laravel route helper ile temel URL’yi blade tarafında hazırla
    const base = @json(route('kategori.form', ['kategori' => '___K___']));
    const url  = base.replace('___K___', encodeURIComponent(kategori));

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

  // Sayfaya dönüşte seçili kategori varsa otomatik getir
  if (kategoriSelect.value) loadKategoriForm();
});
</script>

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kırklareli Belediye Başkanlığı 39 Kent Kart - Başvuru Formu</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #ffffff;
            /* color: #ffffff;  <-- SİLİNDİ ki metinler görünür olsun */
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        .form-container {
            background-color: #03a0db;
            color: white;
            border-radius: 12px;
            box-shadow: 0 6px 12px rgba(0,0,0,0.1);
            padding: 30px;
            margin-top: 30px;
        }
        .form-title { color: white; margin-bottom: 20px; font-weight: 700; text-align: center; }
        .form-control { border-radius: 10px; border: 1px solid #ccc; padding: 10px; font-size: 16px; }
        .btn-primary { background-color: #0056b3; border: none; border-radius: 10px; padding: 12px 20px; font-size: 18px; font-weight: 600; transition: background-color .3s ease; }
        .btn-primary:hover { background-color: #004494; }
        footer { margin-top: 20px; font-size: 14px; text-align: center; color: #ffffff; }
        .form-label { font-weight: bold; }
        .text-center p { margin: 0; }
        .header-section { display: flex; align-items: center; justify-content: center; margin-bottom: 20px; }
        .header-section img { margin-right: 20px; width: 100px; filter: brightness(0) invert(1); }
        footer span { color: red; }
    </style>
</head>
<body>
<div class="container mt-4">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="form-container">
                <div class="header-section">
                    <img src="/public/belediye-logo.png" alt="Belediye Logo">
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

                <form action="{{ route('students.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf

                    <div class="mb-3">
                        <label for="kategori" class="form-label">Başvuru Kategorisi</label>
                        <select name="kategori" id="kategori" class="form-control" required>
                            <option value="">Seçiniz</option>
                            <option value="Ogrenci">Öğrenci</option>
                            <option value="Ogretmen">Öğretmen</option>
                            <option value="Belediye">Belediye Personeli</option>
                            <option value="Emniyet">Emniyet Hizmetleri</option>
                            <option value="Jandarma">Jandarma Hizmetleri</option>
                            <option value="Gazi">Gazi veya Gazi Yakını</option>
                            <option value="Sehit">Şehit Yakını</option>
                            <option value="65 Yas Ustu">65 Yaş Üstü</option>
                            <option value="Engelli">Engelli</option>
                            <option value="Engelli Refakatci">Engelli ve Refakatçısı</option>
                            <option value="Posta">Posta Dağıtıcıları</option>
                            <option value="Annekart">Annekart</option>
                            <option value="Sari Basin">Sarı Basın Kartı</option>
                            <option value="Zabita">Belediye Zabıtası</option>
                        </select>
                    </div>

                    <div id="kategori-formu"><!-- AJAX ile buraya alanlar gelecek --></div>

                    <div class="form-check mb-3">
                        <input class="form-check-input" type="checkbox" id="aydinlatma_onay" name="aydinlatma_onay" value="1" required>
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

    kategoriSelect.addEventListener('change', loadKategoriForm);

    function loadKategoriForm() {
        const kategori = kategoriSelect.value;
        if (!kategori) { formDiv.innerHTML = ''; return; }

        fetch(`/kategori-form/${encodeURIComponent(kategori)}`)
            .then(r => {
                if (!r.ok) throw new Error('HTTP ' + r.status);
                return r.text();
            })
            .then(html => { formDiv.innerHTML = html; })
            .catch(() => { formDiv.innerHTML = '<div class="alert alert-danger">Form yüklenemedi.</div>'; });
    }

    // Sayfa yenilendiğinde daha önce seçili kategori varsa tekrar yükle:
    if (kategoriSelect.value) loadKategoriForm();
});
</script>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
