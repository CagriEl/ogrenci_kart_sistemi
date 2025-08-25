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
        // Kategoriler ve her biri için 5 kayıt
        $kategoriler = ['Ogrenci','Ogretmen','Emniyet','Jandarma','Belediye','Gazi','Sehit'];

        foreach ($kategoriler as $kategori) {
            for ($i = 1; $i <= 5; $i++) {
                // --- Dosya yardımcıları ---
                $vesikalikPath = 'vesikalik/' . Str::uuid() . '.jpg';
                $kimlikOnPath  = 'kimlikler/' . Str::uuid() . '.jpg';
                $kimlikArkaPath= 'kimlikler/' . Str::uuid() . '.jpg';

                // İnternetten placeholder indirmeyi dene, olmazsa yerel sahte veri yaz
                $this->putImageOrFake($vesikalikPath, 'https://picsum.photos/640/480');
                $this->putImageOrFake($kimlikOnPath,  'https://picsum.photos/640/480');
                $this->putImageOrFake($kimlikArkaPath,'https://picsum.photos/640/480');

                // Öğrenci için öğrenci belgesi, Emniyet/Jandarma/Belediye/Gazi/Sehit için kurum yazısı PDF
                $ogrenciBelgesi = null;
                $belediyeYazi   = null;

                if ($kategori === 'Ogrenci') {
                    $ogrenciBelgesi = 'ogrenci_belgeleri/' . Str::uuid() . '.pdf';
                    $this->putPdfFake($ogrenciBelgesi, 'Öğrenci Belgesi (Sahte)');
                }

                if (in_array($kategori, ['Emniyet','Jandarma','Belediye','Gazi','Sehit'])) {
                    $belediyeYazi = 'ekler/' . Str::uuid() . '.pdf';
                    $this->putPdfFake($belediyeYazi, $kategori . ' Belgesi (Sahte)');
                }

                // 11 haneli benzersiz TC (gerçek doğrulama yapmıyoruz, sadece test için)
                $tc = (string) random_int(10000000000, 99999999999);

                // Kategoriye özel meta alanları
                $meta = [];
                if ($kategori === 'Ogretmen') {
                    $meta['brans'] = ['Matematik','Türkçe','Tarih','Biyoloji','Kimya'][array_rand([0,1,2,3,4])];
                    $meta['okul']  = 'Okul ' . $i;
                } elseif ($kategori === 'Ogrenci') {
                    $meta['ogrenci_no'] = (string) random_int(100000, 999999);
                    $meta['okul_adi']   = 'Üniversite ' . $i;
                }

                Student::create([
                    'kategori'        => $kategori,
                    'ad_soyad'        => $kategori . ' Başvuru ' . $i,
                    'tc'              => $tc,
                    'telefon'         => '05' . random_int(100, 599) . random_int(1000000, 9999999),
                    'adres'           => 'Adres Mah. Sok. No:' . random_int(1, 200) . ' / İlçe',
                    'email'           => Str::slug($kategori) . $i . '@example.com',
                    'baba_adi'        => 'Baba ' . $i,
                    'dogum_tarihi'    => now()->subYears(random_int(16, 45))->format('Y-m-d'),
                    'dogum_yeri'      => 'Şehir ' . random_int(1, 81),
                    'bolum'           => $kategori === 'Ogrenci' ? 'Bölüm ' . $i : null,

                    // Dosyalar
                    'ogrenci_belgesi' => $ogrenciBelgesi,
                    'belediye_yazi'   => $belediyeYazi,
                    'vesikalik'       => $vesikalikPath,
                    // 'kimlik_on'       => $kimlikOnPath,
                    // 'kimlik_arka'     => $kimlikArkaPath,

                    // Genel
                    'aydinlatma_onay' => true,
                    'durum'           => 'İşlem Bekliyor',
                    'sicil'           => null,

                    // Kategoriye özel diğer veriler
                    'meta'            => $meta,
                ]);
            }
        }
    }

    /**
     * İnternetten görsel indirmeyi dener; başarısız olursa yerel sahte JPG içeriği yazar.
     */
    private function putImageOrFake(string $path, string $url): void
    {
        $bytes = @file_get_contents($url);
        if ($bytes === false) {
            // Basit sahte jpg header + rastgele veri (görüntülenebilir olmayabilir ama test için yeterli)
            $bytes = random_bytes(1024);
        }
        Storage::disk('public')->put($path, $bytes);
    }

    /**
     * Sahte PDF içeriği yazar (gerçek PDF değil ama indirme butonunu test etmek için yeterli).
     */
    private function putPdfFake(string $path, string $title = 'Test PDF'): void
    {
        $content = "%PDF-1.4\n% Fake PDF for testing: {$title}\n";
        $content .= "1 0 obj<<>>endobj\ntrailer<<>>\n%%EOF";
        Storage::disk('public')->put($path, $content);
    }
}
