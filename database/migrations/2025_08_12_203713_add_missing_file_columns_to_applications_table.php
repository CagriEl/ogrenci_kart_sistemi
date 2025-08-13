<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('applications', function (Blueprint $table) {
            // Dosya/kayıt kolonları – yoksa ekle
            if (!Schema::hasColumn('applications', 'ogrenci_belgesi')) {
                $table->string('ogrenci_belgesi')->nullable()->after('bolum');
            }
            if (!Schema::hasColumn('applications', 'belediye_yazi')) {
                // Emniyet/Jandarma/Belediye/Gazi/Şehit PDF’i
                $table->string('belediye_yazi')->nullable()->after('ogrenci_belgesi');
            }
            if (!Schema::hasColumn('applications', 'vesikalik')) {
                $table->string('vesikalik')->nullable()->after('belediye_yazi');
            }
            // if (!Schema::hasColumn('applications', 'kimlik_on')) {
            //     $table->string('kimlik_on')->nullable()->after('vesikalik');
            // }
            // if (!Schema::hasColumn('applications', 'kimlik_arka')) {
            //     $table->string('kimlik_arka')->nullable()->after('kimlik_on');
            // }
            if (!Schema::hasColumn('applications', 'sicil')) {
                $table->string('sicil')->nullable()->after('durum');
            }
            if (!Schema::hasColumn('applications', 'meta')) {
                $table->json('meta')->nullable()->after('sicil');
            }
        });
    }

    public function down(): void
    {
        Schema::table('applications', function (Blueprint $table) {
            if (Schema::hasColumn('applications', 'ogrenci_belgesi')) $table->dropColumn('ogrenci_belgesi');
            if (Schema::hasColumn('applications', 'belediye_yazi'))   $table->dropColumn('belediye_yazi');
            if (Schema::hasColumn('applications', 'vesikalik'))       $table->dropColumn('vesikalik');
            if (Schema::hasColumn('applications', 'kimlik_on'))       $table->dropColumn('kimlik_on');
            if (Schema::hasColumn('applications', 'kimlik_arka'))     $table->dropColumn('kimlik_arka');
            if (Schema::hasColumn('applications', 'sicil'))           $table->dropColumn('sicil');
            if (Schema::hasColumn('applications', 'meta'))            $table->dropColumn('meta');
        });
    }
};
