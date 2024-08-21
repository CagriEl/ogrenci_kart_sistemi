<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Öğrenci Başvuru Formu</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-4">
        <h2 class="text-center">Kırklareli Belediye Başkanlığı
            <br>39 Kent Kart<br>Öğrenci Başvuru Formu</h2>

        @if(session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif
    
        <form action="{{ route('students.store') }}" method="POST" enctype="multipart/form-data" id="studentForm">
            @csrf
            <div class="row mb-3">
                <div class="col-md-6">
                    <label for="ad_soyad" class="form-label">Ad Soyad</label>
                    <input type="text" class="form-control" id="ad_soyad" name="ad_soyad" required>
                </div>
                <div class="col-md-6">
                    <label for="tc" class="form-label">TC Kimlik No</label>
                    <input type="text" class="form-control" id="tc" name="tc" maxlength="11" required>
                </div>
            </div>
            <div class="row mb-3">
                <div class="col-md-6">
                    <label for="baba_adi" class="form-label">Baba Adı</label>
                    <input type="text" class="form-control" id="baba_adi" name="baba_adi" required>
                </div>
                <div class="col-md-6">
                    <label for="dogum_tarihi" class="form-label">Doğum Tarihi</label>
                    <input type="date" class="form-control" id="dogum_tarihi" name="dogum_tarihi" required>
                </div>
            </div>
            <div class="row mb-3">
                <div class="col-md-6">
                    <label for="telefon" class="form-label">Telefon</label>
                    <input type="text" class="form-control" id="telefon" name="telefon" required>
                </div>
                <div class="col-md-6">
                    <label for="dogum_yeri" class="form-label">Doğum Yeri</label>
                    <input type="text" class="form-control" id="dogum_yeri" name="dogum_yeri" required>
                </div>
            </div>
            <div class="row mb-3">
                <div class="col-md-6">
                    <label for="adres" class="form-label">Adres</label>
                    <input type="text" class="form-control" id="adres" name="adres" required>
                </div>
                <div class="col-md-6">
                    <label for="email" class="form-label">E-Mail</label>
                    <input type="email" class="form-control" id="email" name="email" required>
                </div>
            </div>
            <div class="row mb-3">
                <div class="col-md-6">
                    <label for="bolum" class="form-label">Kazandığınız Bölüm</label>
                    <input type="text" class="form-control" id="bolum" name="bolum" required>
                </div>
                <div class="col-md-6">
                    <label for="ogrenci_belgesi" class="form-label">Öğrenci Belgesi (PDF)</label>
                    <input type="file" class="form-control" id="ogrenci_belgesi" name="ogrenci_belgesi" accept=".pdf" required>
                </div>
            </div>
            <div class="row mb-3">
                <div class="col-md-6">
                    <label for="kimlik_on" class="form-label">Kimlik Ön Görsel</label>
                    <input type="file" class="form-control" id="kimlik_on" name="kimlik_on" accept="image/*" capture="camera">
                </div>
                <div class="col-md-6">
                    <label for="kimlik_arka" class="form-label">Kimlik Arka Görsel</label>
                    <input type="file" class="form-control" id="kimlik_arka" name="kimlik_arka" accept="image/*" capture="camera">
                </div>
            </div>
            <div class="row mb-3">
                <div class="col-md-6">
                    <label for="vesikalik" class="form-label">Vesikalık Fotoğraf</label>
                    <input type="file" class="form-control" id="vesikalik" name="vesikalik" accept="image/*" capture="camera">
                </div>
            </div>
            <div class="form-check mb-3">
                <input class="form-check-input" type="checkbox" id="aydinlatma_onay" name="aydinlatma_onay" required>
                <label class="form-check-label" for="aydinlatma_onay">
                    <a href="https://api.kirklarelibelediyesi.com/files/dokuman/kirklareli-kvkk.pdf">Aydınlatma metnini</a> okudum onaylıyorum.
                </label>
            </div>
            <button type="submit" class="btn btn-primary">Başvuru Gönder</button>
        </form>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
<footer>


</footer>
</html>

