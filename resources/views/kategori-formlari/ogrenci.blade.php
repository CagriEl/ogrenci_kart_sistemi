<div class="form-section-title">Kişisel Bilgiler</div>
<div class="row g-3 mb-3">
  <div class="col-md-6">
    <label for="ad_soyad" class="form-label">Ad Soyad</label>
    <input type="text" class="form-control" id="ad_soyad" name="ad_soyad" required value="{{ old('ad_soyad') }}" placeholder="Adınız ve soyadınız" autocomplete="name">
  </div>
  <div class="col-md-6">
    <label for="tc" class="form-label">TC Kimlik No</label>
    <input type="text" class="form-control" id="tc" name="tc" maxlength="11" required value="{{ old('tc') }}" placeholder="11 haneli TC kimlik numarası" inputmode="numeric">
  </div>
  <div class="col-md-6">
    <label for="baba_adi" class="form-label">Baba Adı</label>
    <input type="text" class="form-control" id="baba_adi" name="baba_adi" required value="{{ old('baba_adi') }}" placeholder="Baba adı">
  </div>
  <div class="col-md-6">
    <label for="dogum_tarihi" class="form-label">Doğum Tarihi</label>
    <input type="date" id="dogum_tarihi" class="form-control" name="dogum_tarihi" required min="1950-01-01" max="2015-12-31" value="{{ old('dogum_tarihi') }}">
  </div>
  <div class="col-md-6">
    <label for="dogum_yeri" class="form-label">Doğum Yeri</label>
    <input type="text" class="form-control" id="dogum_yeri" name="dogum_yeri" required value="{{ old('dogum_yeri') }}" placeholder="İl / ilçe">
  </div>
  <div class="col-md-6">
    <label for="bolum" class="form-label">Kazandığınız Bölüm</label>
    <input type="text" class="form-control" id="bolum" name="bolum" required value="{{ old('bolum') }}" placeholder="Örn. Bilgisayar Mühendisliği">
  </div>
</div>

<div class="form-section-title">İletişim Bilgileri</div>
<div class="row g-3 mb-3">
  <div class="col-md-6">
    <label for="telefon" class="form-label">Telefon</label>
    <input type="tel" class="form-control" id="telefon" name="telefon" required maxlength="11" pattern="\d{10,11}" value="{{ old('telefon') }}" placeholder="05XXXXXXXXX" inputmode="numeric">
  </div>
  <div class="col-md-6">
    <label for="email" class="form-label">E-Mail</label>
    <input type="email" class="form-control" id="email" name="email" required value="{{ old('email') }}" placeholder="ornek@eposta.com" autocomplete="email">
  </div>
  <div class="col-12">
    <label for="adres" class="form-label">Adres</label>
    <input type="text" class="form-control" id="adres" name="adres" required value="{{ old('adres') }}" placeholder="Açık adresiniz">
  </div>
</div>

<div class="form-section-title">Belgeler</div>
<div class="row g-3 mb-1">
  <div class="col-md-6">
    <label for="ogrenci_belgesi" class="form-label">Öğrenci Belgesi (PDF)</label>
    <input type="file" class="form-control" id="ogrenci_belgesi" name="ogrenci_belgesi" accept="application/pdf" required>
    <div class="form-hint">e-Devlet’ten aldığınız öğrenci belgesini PDF olarak yükleyiniz.</div>
  </div>
  <div class="col-md-6">
    <label for="vesikalik" class="form-label">Vesikalık Fotoğraf (JPG / JPEG)</label>
    <input type="file" class="form-control" id="vesikalik" name="vesikalik" accept=".jpg,.jpeg,image/jpeg" required>
    <div class="form-hint">Yalnızca JPG veya JPEG. Vesikalık dışında fotoğraf kabul edilmez.</div>
  </div>
</div>
