<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Öğrenci Başvuru Formu</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #ffffff; /* Arka planı mavi yap */
            color: #ffffff;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        .form-container {
            background-color: #03a0db;
            color: white;
            border-radius: 12px;
            box-shadow: 0 6px 12px rgba(0, 0, 0, 0.1);
            padding: 30px;
            margin-top: 30px;
        }

        .form-title {
            color: white;
            margin-bottom: 20px;
            font-weight: 700;
            text-align: center;
        }

        .form-control {
            border-radius: 10px;
            border: 1px solid #ccc;
            padding: 10px;
            font-size: 16px;
        }

        .btn-primary {
            background-color: #0056b3;
            border: none;
            border-radius: 10px;
            padding: 12px 20px;
            font-size: 18px;
            font-weight: 600;
            transition: background-color 0.3s ease;
        }

        .btn-primary:hover {
            background-color: #004494;
        }

        footer {
            margin-top: 20px;
            font-size: 14px;
            text-align: center;
            color: #ffffff;
        }

        .form-label {
            font-weight: bold;
        }

        .text-center p {
            margin: 0;
        }

        .header-section {
            display: flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 20px;
        }

        .header-section img {
            margin-right: 20px;
            width: 100px;
            filter: brightness(0) invert(1); /* Logoyu beyaz yap */
        }

        footer span {
            color: red;
        }
    </style>
</head>
<body>
    <div class="container mt-4">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="form-container">
                    <div class="header-section">
                        <img src="/public/belediye_logo.webp" alt="Belediye Logo">
                        <h2 class="form-title">Kırklareli Belediye Başkanlığı<br>39 Kent Kart<br>Öğrenci Başvuru Formu</h2>
                    </div>

                    <form action="{{ route('students.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="ad_soyad" class="form-label">Ad Soyad</label>
                                <input type="text" class="form-control" id="ad_soyad" name="ad_soyad" required autocomplete="off">
                            </div>
                            <div class="col-md-6">
                                <label for="tc" class="form-label">TC Kimlik No</label>
                                <input type="text" class="form-control" id="tc" name="tc" maxlength="11" required autocomplete="off">
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="baba_adi" class="form-label">Baba Adı</label>
                                <input type="text" class="form-control" id="baba_adi" name="baba_adi" required autocomplete="off">
                            </div>
                            <div class="col-md-6">
                                <label for="dogum_tarihi" class="form-label">Doğum Tarihi</label>
                                <input type="date" class="form-control" id="dogum_tarihi" name="dogum_tarihi" required autocomplete="off">
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="telefon" class="form-label">Telefon</label>
                                <input type="text" class="form-control" id="telefon" name="telefon" required autocomplete="off">
                            </div>
                            <div class="col-md-6">
                                <label for="dogum_yeri" class="form-label">Doğum Yeri</label>
                                <input type="text" class="form-control" id="dogum_yeri" name="dogum_yeri" required autocomplete="off">
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="adres" class="form-label">Adres</label>
                                <input type="text" class="form-control" id="adres" name="adres" required autocomplete="off">
                            </div>
                            <div class="col-md-6">
                                <label for="email" class="form-label">E-Mail</label>
                                <input type="email" class="form-control" id="email" name="email" required autocomplete="off">
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="bolum" class="form-label">Kazandığınız Bölüm</label>
                                <input type="text" class="form-control" id="bolum" name="bolum" required autocomplete="off">
                            </div>
                            <div class="col-md-6">
                                <label for="ogrenci_belgesi" class="form-label">Öğrenci Belgesi (PDF)</label>
                                <input type="file" class="form-control" id="ogrenci_belgesi" name="ogrenci_belgesi" accept="application/pdf" required>
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="kimlik_on" class="form-label">Kimlik Ön Görsel</label>
                                <input type="file" class="form-control" id="kimlik_on" name="kimlik_on" accept="image/*" required>
                            </div>
                            <div class="col-md-6">
                                <label for="kimlik_arka" class="form-label">Kimlik Arka Görsel</label>
                                <input type="file" class="form-control" id="kimlik_arka" name="kimlik_arka" accept="image/*" required>
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-12">
                                <label for="vesikalik" class="form-label">Vesikalık Fotoğraf</label>
                                <input type="file" class="form-control" id="vesikalik" name="vesikalik" accept="image/*" required>
                            </div>
                        </div>

                        <div class="form-check mb-3">
                            <input class="form-check-input" type="checkbox" id="aydinlatma_onay" name="aydinlatma_onay" required>
                            <label class="form-check-label" for="aydinlatma_onay">
                                <a href="https://api.kirklarelibelediyesi.com/files/dokuman/kirklareli-kvkk.pdf" style="color: #0056b3;">Aydınlatma metnini</a> okudum onaylıyorum.
                            </label>
                        </div>

                        <div class="text-center mt-4">
                            <button type="submit" class="btn btn-primary">Başvuru Gönder</button>
                        </div>
                            @if(session('success'))
                                <div class="alert alert-success">
                                    {{ session('success') }}
                                </div>
                            @endif

                    </form>

                    <footer>
                        <p>Kırklareli Belediye Başkanlığı Bilgi İşlem Müdürlüğü tarafından <span>❤</span> ile kodlandı.</p>
                    </footer>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
