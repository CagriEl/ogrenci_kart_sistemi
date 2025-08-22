<form action="{{ route('students.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @if(session('success'))
                        <div class="alert alert-success">
                            {{ session('success') }}
                        </div>
                    @endif
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
                                <input type="date" id="dogum_tarihi" class="form-control" name="dogum_tarihi" required  autocomplete="off" min="1950-01-01" max="2007-12-31">
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="telefon" class="form-label">Telefon</label>
                                <input type="tel" class="form-control" id="telefon" name="telefon" required autocomplete="off" maxlength="11" pattern="\d{10,11}" title="Telefon numarası 10 veya 11 haneli olmalıdır.">
                            </div>
                            <div class="col-md-6">
                                <label for="dogum_yeri" class="form-label">Doğum Yeri</label>
                                <input type="text" class="form-control" id="dogum_yeri" name="dogum_yeri" required autocomplete="off">
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="adres" class="form-label">Adres</label>
                                <input type="textarea" class="form-control" id="adres" name="adres" required autocomplete="off">
                            </div>
                            <div class="col-md-6">
                                <label for="email" class="form-label">E-Mail</label>
                                <input type="email" class="form-control" id="email" name="email" required autocomplete="off">
                            </div>
                        </div>

                       
                            <div class="col-md-12">
                                <label for="belediye_yazi" class="form-label">Belediye Başkanlığından Resmi Yazı (PDF)</label>
                                <input type="file" class="form-control" id="belediye_yazi" name="belediye_yazi" accept="application/pdf" required>
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-12">
                                <label for="vesikalik" class="form-label">Vesikalık Fotoğraf
                                <br>(Lütfen vesikalık fotoğraflarınızı yükleyiniz. Vesikalık fotoğraf dışında yüklenen fotoğraflar işleme alınmayacaktır.)</label>
                                <input type="file" class="form-control" id="vesikalik" name="vesikalik" accept="image/*" required>
                            </div>
                        </div>

                    </form>

                    
                </div>
            </div>
        </div>
    </div>

    