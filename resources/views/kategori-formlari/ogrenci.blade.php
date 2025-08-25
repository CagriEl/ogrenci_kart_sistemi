<div class="row mb-3">
  <div class="col-md-6">
    <label for="ad_soyad" class="form-label">Ad Soyad</label>
    <input type="text" class="form-control" id="ad_soyad" name="ad_soyad" required value="{{ old('ad_soyad') }}">
  </div>
  <div class="col-md-6">
    <label for="tc" class="form-label">TC Kimlik No</label>
    <input type="text" class="form-control" id="tc" name="tc" maxlength="11" required value="{{ old('tc') }}">
  </div>
</div>

<div class="row mb-3">
  <div class="col-md-6">
    <label for="baba_adi" class="form-label">Baba Adı</label>
    <input type="text" class="form-control" id="baba_adi" name="baba_adi" required value="{{ old('baba_adi') }}">
  </div>
  <div class="col-md-6">
    <label for="dogum_tarihi" class="form-label">Doğum Tarihi</label>
    <input type="date" id="dogum_tarihi" class="form-control" name="dogum_tarihi" required min="1950-01-01" max="2009-12-31" value="{{ old('dogum_tarihi') }}">
  </div>
</div>

<div class="row mb-3">
  <div class="col-md-6">
    <label for="telefon" class="form-label">Telefon</label>
    <input type="tel" class="form-control" id="telefon" name="telefon" required maxlength="11" pattern="\d{10,11}" value="{{ old('telefon') }}">
  </div>
  <div class="col-md-6">
    <label for="dogum_yeri" class="form-label">Doğum Yeri</label>
    <input type="text" class="form-control" id="dogum_yeri" name="dogum_yeri" required value="{{ old('dogum_yeri') }}">
  </div>
</div>

<div class="row mb-3">
  <div class="col-md-6">
    <label for="adres" class="form-label">Adres</label>
    <input type="text" class="form-control" id="adres" name="adres" required value="{{ old('adres') }}">
  </div>
  <div class="col-md-6">
    <label for="email" class="form-label">E-Mail</label>
    <input type="email" class="form-control" id="email" name="email" required value="{{ old('email') }}">
  </div>
</div>

<div class="row mb-3">
  <div class="col-md-6">
    <label for="bolum" class="form-label">Kazandığınız Bölüm</label>
    <input type="text" class="form-control" id="bolum" name="bolum" required value="{{ old('bolum') }}">
  </div>
  <div class="col-md-6">
    <label for="ogrenci_belgesi" class="form-label">Öğrenci Belgesi (PDF)</label>
    <input type="file" class="form-control" id="ogrenci_belgesi" name="ogrenci_belgesi" accept="application/pdf" required>
  </div>
</div>

<div class="mb-3">
  <label for="vesikalik" class="form-label">Vesikalık Fotoğraf</label>
  <input type="file" class="form-control" id="vesikalik" name="vesikalik" accept="image/*" required>
</div>
