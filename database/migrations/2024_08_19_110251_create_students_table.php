<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('students', function (Blueprint $table) {
            $table->id();
            $table->string('tc', 11)->unique()->nullable(false);
            $table->string('ad_soyad')->nullable(false);
            $table->string('baba_adi')->nullable(false);
            $table->date('dogum_tarihi')->nullable(false);
            $table->string('telefon')->nullable(false);
            $table->string('dogum_yeri')->nullable(false);
            $table->string('adres')->nullable(false);
            $table->string('email')->nullable(false);
            $table->string('bolum')->nullable(false);
            $table->string('ogrenci_belgesi')->nullable();
            $table->string('kimlik_on')->nullable();
            $table->string('kimlik_arka')->nullable();
            $table->string('vesikalik')->nullable();
            $table->boolean('aydinlatma_onay')->default(false);
            $table->string('durum')->default('İşlem Bekliyor');
            $table->string('sicil')->nullable(); // 'after' olmadan sütunu ekleyin
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('students');
    }
};
