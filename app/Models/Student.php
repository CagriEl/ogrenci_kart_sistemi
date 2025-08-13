<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Student extends Model
{
    use HasFactory;

    protected $table = 'applications';

    protected $fillable = [
        'kategori',
        'tc','ad_soyad','baba_adi','dogum_tarihi','dogum_yeri',
        'telefon','adres','email','bolum',
        // dosya alanları:
        'ogrenci_belgesi','vesikalik','kimlik_on','kimlik_arka','belediye_yazi',
        'aydinlatma_onay','durum','sicil',
        // kategoriye özel alanları JSON’da tutacağız
        'meta',
    ];

    protected $casts = [
        'meta' => 'array',
        'aydinlatma_onay' => 'boolean',
    ];
}
