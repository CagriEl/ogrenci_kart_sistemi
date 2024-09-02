<?php
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Student;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

class StudentsTableSeeder extends Seeder
{
    public function run()
    {
        for ($i = 0; $i < 25; $i++) {
            // İnternetten görselleri indirip kaydetme
            $vesikalikImageUrl = 'https://picsum.photos/640/480';
            $vesikalikImage = 'vesikalik_fotograflar/' . Str::uuid() . '.jpg';
            Storage::disk('public')->put($vesikalikImage, file_get_contents($vesikalikImageUrl));

            $kimlikOnImageUrl = 'https://picsum.photos/640/480';
            $kimlikOnImage = 'kimlik_fotograflar/' . Str::uuid() . '.jpg';
            Storage::disk('public')->put($kimlikOnImage, file_get_contents($kimlikOnImageUrl));

            $kimlikArkaImageUrl = 'https://picsum.photos/640/480';
            $kimlikArkaImage = 'kimlik_fotograflar/' . Str::uuid() . '.jpg';
            Storage::disk('public')->put($kimlikArkaImage, file_get_contents($kimlikArkaImageUrl));

            

            Student::create([
                'ad_soyad' => 'Öğrenci ' . $i,
                'tc' => '3000000000' . $i, // TC numarasını sabit tutuyoruz
                'telefon' => '0543' . random_int(1000000, 9999999),
                'adres' => 'Adres ' . $i,
                'bolum' => 'Bölüm ' . $i,
                'kimlik_on' => $kimlikOnImage,
                'kimlik_arka' => $kimlikArkaImage,
                'vesikalik' => $vesikalikImage,
                'dogum_tarihi' => now()->subYears(random_int(18, 25))->format('Y-m-d'),
                'baba_adi' => 'Baba ' . $i,
                'dogum_yeri' => 'Şehir ' . $i,
                'email' => 'ogrenci' . $i . '@example.com',
                'aydinlatma_onay' => true,
                'durum' => 'İşlem Bekliyor',
                'sicil' => null,
            ]);
        }
    }
}
