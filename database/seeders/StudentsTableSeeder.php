<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Faker\Factory as Faker;

class StudentsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $faker = Faker::create();

        foreach (range(1, 4000) as $index) {
            DB::table('students')->insert([
                'ad_soyad' => $faker->name,
                'tc' => $faker->unique()->numerify('###########'),
                'baba_adi' => $faker->firstNameMale,
                'dogum_tarihi' => $faker->date(),
                'telefon' => $faker->phoneNumber,
                'dogum_yeri' => $faker->city,
                'adres' => $faker->address,
                'email' => $faker->unique()->safeEmail,
                'bolum' => $faker->word,
                'ogrenci_belgesi' => null, // Öğrenci belgesi (PDF dosyası) için null bırakabilirsiniz
                'kimlik_on' => null, // Kimlik ön görseli için null bırakabilirsiniz
                'kimlik_arka' => null, // Kimlik arka görseli için null bırakabilirsiniz
                'vesikalik' => null, // Vesikalık fotoğraf için null bırakabilirsiniz
                'aydinlatma_onay' => true,
                'durum' => 'İşlem Bekliyor',
                'sicil' => null, // Sicil numarası için null bırakabilirsiniz
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}
