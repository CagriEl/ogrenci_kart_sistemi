<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('applications', function (Blueprint $table) {
            $table->id();
            $table->string('kategori')->nullable();     // Öğrenci / Öğretmen vb.
            $table->string('tc', 11)->unique();
            $table->string('ad_soyad');
            $table->string('baba_adi')->nullable();
            $table->date('dogum_tarihi')->nullable();
            $table->string('dogum_yeri')->nullable();
            $table->string('telefon')->nullable();
            $table->string('adres', 1000)->nullable();
            $table->string('email')->nullable();
            $table->string('bolum')->nullable();
            $table->string('ogrenci_belgesi')->nullable();
            // $table->string('kimlik_on')->nullable();
            // $table->string('kimlik_arka')->nullable();
            $table->string('vesikalik')->nullable();
            $table->boolean('aydinlatma_onay')->default(false);
            $table->string('durum')->default('İşlem Bekliyor');
            $table->string('sicil')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('applications');
    }
};
