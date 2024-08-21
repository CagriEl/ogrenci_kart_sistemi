<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Student extends Model
{
    use HasFactory;

    // Doğru tablo adı belirtildi mi?
    protected $table = 'students'; // varsayılan olarak 'students' tablosu kullanılır

    // Toplu atama için uygun olan alanlar
    protected $fillable = [
        'ad_soyad',
        'tc',
        'baba_adi',
        'dogum_tarihi',
        'telefon',
        'dogum_yeri',
        'adres',
        'email',
        'bolum',
        'ogrenci_belgesi',
        'kimlik_on',
        'kimlik_arka',
        'vesikalik',
        'aydinlatma_onay',
        'durum',
        'sicil'
    ];
}
