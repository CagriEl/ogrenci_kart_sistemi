<?php

namespace App\Http\Controllers;

use App\Models\Student;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
// use Illuminate\Support\Facades\Mail;

class StudentController extends Controller
{
    /**
     * Ziyaretçi: Başvuru kaydet (public form POST)
     */
    public function store(Request $request)
    {
        $kategori = $request->input('kategori'); // Ogrenci, Ogretmen, Emniyet, Jandarma, Belediye, Gazi, Sehit, ...

        // Temel doğrulama kuralları
        $baseRules = [
            'kategori'        => ['required', 'string', 'max:100'],
            'ad_soyad'        => ['required', 'string', 'max:255'],
            'tc'              => ['required', 'digits:11'],
            'telefon'         => ['required', 'regex:/^\d{10,11}$/'],
            'adres'           => ['required', 'string', 'max:1000'],
            'email'           => ['required', 'email', 'max:255'],
            'baba_adi'        => ['nullable', 'string', 'max:255'],
            'dogum_tarihi'    => ['nullable', 'date'],
            'dogum_yeri'      => ['nullable', 'string', 'max:255'],
            'vesikalik'       => ['required', 'image', 'max:4096'], // 4MB
            'kimlik_on'       => ['nullable', 'image', 'max:4096'],
            'kimlik_arka'     => ['nullable', 'image', 'max:4096'],
            'aydinlatma_onay' => ['accepted'],
        ];

        // Kategoriye özel kurallar
        $extraRules = [];
        if ($kategori === 'Ogrenci') {
            $extraRules['bolum']            = ['required', 'string', 'max:255'];
            $extraRules['ogrenci_belgesi']  = ['required', 'file', 'mimes:pdf', 'max:10240']; // 10MB
        } elseif (in_array($kategori, ['Emniyet','Jandarma','Ogretmen','Belediye','Gazi','Sehit'], true)) {
            $extraRules['belediye_yazi']    = ['required', 'file', 'mimes:pdf', 'max:10240'];
        }

        $data = $request->validate($baseRules + $extraRules);

        // Dosyaları kaydet
        $paths = [];
        if ($request->hasFile('vesikalik')) {
            $paths['vesikalik'] = $request->file('vesikalik')->store('vesikalik', 'public');
        }
        if ($request->hasFile('kimlik_on')) {
            $paths['kimlik_on'] = $request->file('kimlik_on')->store('kimlikler', 'public');
        }
        if ($request->hasFile('kimlik_arka')) {
            $paths['kimlik_arka'] = $request->file('kimlik_arka')->store('kimlikler', 'public');
        }
        if ($request->hasFile('ogrenci_belgesi')) {
            $paths['ogrenci_belgesi'] = $request->file('ogrenci_belgesi')->store('ogrenci_belgeleri', 'public');
        }
        if ($request->hasFile('belediye_yazi')) {
            $paths['belediye_yazi'] = $request->file('belediye_yazi')->store('resmi_belgeler', 'public');
        }

        // Kayıt
        $student = new Student();
        $student->kategori        = $data['kategori'];
        $student->ad_soyad        = $data['ad_soyad'];
        $student->tc              = $data['tc'];
        $student->telefon         = $data['telefon'];
        $student->adres           = $data['adres'];
        $student->email           = $data['email'];
        $student->baba_adi        = $data['baba_adi'] ?? null;
        $student->dogum_tarihi    = $data['dogum_tarihi'] ?? null;
        $student->dogum_yeri      = $data['dogum_yeri'] ?? null;
        $student->bolum           = $data['bolum'] ?? null;
        $student->ogrenci_belgesi = $paths['ogrenci_belgesi'] ?? null;
        $student->belediye_yazi   = $paths['belediye_yazi'] ?? null;
        $student->vesikalik       = $paths['vesikalik'] ?? null;
        $student->kimlik_on       = $paths['kimlik_on'] ?? null;
        $student->kimlik_arka     = $paths['kimlik_arka'] ?? null;
        $student->aydinlatma_onay = $request->boolean('aydinlatma_onay');
        $student->durum           = 'İşlem Bekliyor';
        $student->sicil           = $student->sicil ?? null;

        $student->save();

        return back()->with('success', 'Başvurunuz alınmıştır. Teşekkür ederiz.');
    }

    /**
     * Admin: Başvuru listesi
     * - Ana listede görünmeyecek durumlar: Sicil Oluşturuldu, Sicil Oluştu - Tahakkuk Girildi, Kart Basıldı(+varyasyon)
     * - Kategori seçilmemişse: tümü listelenir, Öğrenci olanlar en üstte sıralanır
     * - Sağ üst rozet: "Basılacak kart" = Sicil Oluşturuldu + Sicil Oluştu - Tahakkuk Girildi
     */
    public function adminIndex(Request $request)
    {
        $selectedKategori = $request->get('kategori'); // "" (Tümü) veya spesifik bir kategori

        $excludeFromMain = [
            'Sicil Oluşturuldu',
            'Sicil Oluştu - Tahakkuk Girildi',
            'Kart Basıldı',
            'Kart Basildi', // olası i/ı varyasyonu
        ];

        $query = Student::query()->whereNotIn('durum', $excludeFromMain);

        if ($selectedKategori !== null && $selectedKategori !== '') {
            $query->where('kategori', $selectedKategori)
                  ->orderByDesc('created_at');
        } else {
            // Tümü gelsin, fakat Öğrenci en üstte
            $query->orderByRaw("CASE WHEN kategori = 'Ogrenci' THEN 0 ELSE 1 END")
                  ->orderByDesc('created_at');
        }

        $students = $query->paginate(15)->withQueryString();

        // Basılacak kart sayısı (basıma adaylar)
        $pendingForPrint = ['Sicil Oluşturuldu', 'Sicil Oluştu - Tahakkuk Girildi'];
        $basilacakKartSayisi = Student::whereIn('durum', $pendingForPrint)->count();

        $kategoriler = [
            'Ogrenci','Ogretmen','Belediye','Emniyet','Jandarma',
            'Gazi','Sehit','65 Yas Ustu','Engelli','Engelli Refakatci',
            'Posta','Annekart','Sari Basin','Zabita',
        ];

        return view('admin.students.index', [
            'students'            => $students,
            'kategoriler'         => $kategoriler,
            'selectedKategori'    => $selectedKategori ?? '',
            'basilacakKartSayisi' => $basilacakKartSayisi,
        ]);
    }

    /**
     * Admin: Düzenleme formu
     */
    public function edit(Student $student)
    {
        return view('admin.students.edit', compact('student'));
    }

    /**
     * Admin: Güncelle
     * - "Kart Basıldı" işaretlenirse durum direkt "Kart Basıldı" ve "Basılmış Kartlar" sayfasına yönlenir
     * - Aksi halde formdan "sicil" dolu gelirse (ve mevcut durum "Kart Basıldı" değilse) -> "Sicil Oluşturuldu"
     * - Kaydettikten sonra ana listeye yönlendir (sicil girildiyse ana listeden kaybolur, "Sicil Oluşturulanlar"a düşer)
     */
    public function update(Request $request, Student $student)
    {
        // Hepsini "sometimes" yapalım ki sadece sicil gönderilse bile güncellensin
        $rules = [
            'ad_soyad'        => ['sometimes','string','max:255'],
            'tc'              => ['sometimes','digits:11'],
            'telefon'         => ['sometimes','regex:/^\d{10,11}$/'],
            'adres'           => ['sometimes','string','max:1000'],
            'email'           => ['sometimes','email','max:255'],
            'baba_adi'        => ['sometimes','nullable','string','max:255'],
            'dogum_tarihi'    => ['sometimes','nullable','date'],
            'dogum_yeri'      => ['sometimes','nullable','string','max:255'],
            'bolum'           => ['sometimes','nullable','string','max:255'],
            'durum'           => ['sometimes','nullable','string','max:255'],
            'sicil'           => ['sometimes','nullable','string','max:255'],
            'aydinlatma_onay' => ['sometimes','boolean'],

            // Dosyalar (opsiyonel)
            'vesikalik'       => ['sometimes','file','image','max:4096'],
            'kimlik_on'       => ['sometimes','file','image','max:4096'],
            'kimlik_arka'     => ['sometimes','file','image','max:4096'],
            'ogrenci_belgesi' => ['sometimes','file','mimes:pdf','max:10240'],
            'belediye_yazi'   => ['sometimes','file','mimes:pdf','max:10240'],

            // (varsa) Kart basıldı checkbox
            'kart_basildi'    => ['sometimes','boolean'],
        ];

        $data = $request->validate($rules);

        // ---- Dosyalar (gönderilmişse güncelle) ----
        foreach (['vesikalik','kimlik_on','kimlik_arka','ogrenci_belgesi','belediye_yazi'] as $f) {
            if ($request->hasFile($f)) {
                if ($student->$f) {
                    Storage::disk('public')->delete($student->$f);
                }
                $folder = match ($f) {
                    'vesikalik'       => 'vesikalik',
                    'kimlik_on',
                    'kimlik_arka'     => 'kimlikler',
                    'ogrenci_belgesi' => 'ogrenci_belgeleri',
                    'belediye_yazi'   => 'resmi_belgeler',
                    default           => 'uploads',
                };
                $student->$f = $request->file($f)->store($folder, 'public');
            }
        }

        // ---- Basit alanlar: sadece gönderilenleri set et ----
        foreach (['ad_soyad','tc','telefon','adres','email','baba_adi','dogum_tarihi','dogum_yeri','bolum','sicil','durum'] as $field) {
            if (array_key_exists($field, $data)) {
                $student->$field = $data[$field];
            }
        }
        if (array_key_exists('aydinlatma_onay', $data)) {
            $student->aydinlatma_onay = (bool)$data['aydinlatma_onay'];
        }

        // 1) "Kart Basıldı" işaretli geldiyse -> kesin metinle set et ve ilgili sayfaya dön
        if ($request->boolean('kart_basildi')) {
            $student->durum = 'Kart Basıldı';
            $student->save();

            return redirect()
                ->route('admin.students.basilan_kartlar')
                ->with('success', 'Kart basıldı olarak işaretlendi ve listesi güncellendi.');
        }

        // 2) Aksi halde: sicil doluysa ve henüz Kart Basıldı değilse -> Sicil Oluşturuldu
        if (filled($student->sicil) && $student->durum !== 'Kart Basıldı' && $student->durum !== 'Kart Basildi') {
            $student->durum = 'Sicil Oluşturuldu';
        }

        $student->save();

        // Ana listeye dön (orada bu durumlar görünmediği için kayıttan düşer)
        return redirect()
            ->route('admin.students.index')
            ->with('success', 'Kayıt güncellendi. Sicil girildiyse ana listeden kaldırıldı ve Sicil Oluşturulanlar’a taşındı.');
    }

    /**
     * Admin: Sil
     */
    public function destroy(Student $student)
    {
        foreach (['vesikalik','kimlik_on','kimlik_arka','ogrenci_belgesi','belediye_yazi'] as $f) {
            if ($student->$f) {
                Storage::disk('public')->delete($student->$f);
            }
        }
        $student->delete();

        return back()->with('success', 'Kayıt silindi.');
    }

    /**
     * Admin: Dosya indir
     */
    public function downloadFile($id, $file_type)
    {
        $student = Student::findOrFail($id);

        $allowed = ['vesikalik','kimlik_on','kimlik_arka','ogrenci_belgesi','belediye_yazi'];
        if (! in_array($file_type, $allowed, true)) {
            return back()->with('error', 'Geçersiz dosya tipi.');
        }

        $path = $student->$file_type;
        if (!$path || !Storage::disk('public')->exists($path)) {
            return back()->with('error', 'Dosya bulunamadı.');
        }

        return response()->download(storage_path('app/public/'.$path));
    }

    /**
     * Admin: Durum güncelle (satırdaki "Onayla" vb.)
     */
    public function updateStatus(Request $request, $id)
    {
        $request->validate([
            'durum' => ['required', 'string', 'max:255'],
        ]);

        $student = Student::findOrFail($id);
        $student->durum = $request->input('durum');
        $student->save();

        return back()->with('success', 'Durum güncellendi: '.$student->durum);
    }

    /**
     * Admin: Eksik Belge Bildirimi (modal POST)
     */
    public function sendEksikBelgeMail(Request $request, $id)
    {
        $data = $request->validate([
            'aciklama' => ['required', 'string', 'max:5000'],
            'durum'    => ['sometimes','nullable','string','max:255'],
        ]);

        $student = Student::findOrFail($id);

        if (!empty($data['durum'])) {
            $student->durum = $data['durum'];
            $student->save();
        }

        // Mail gönderimi istersen aç:
        // Mail::to($student->email)->send(new \App\Mail\EksikBelgeMail($student, $data['aciklama']));

        Log::info('Eksik Belge bildirimi', [
            'student_id' => $student->id,
            'email'      => $student->email,
            'aciklama'   => $data['aciklama'],
            'durum'      => $student->durum,
        ]);

        return back()->with('success', 'Eksik belge bildirimi işlendi.');
    }

    /**
     * Onaylananlar (opsiyonel sayfa)
     */
    public function approvedStudents()
    {
        $students = Student::where('durum', 'Onaylandı')->latest()->paginate(15);

        return view('admin.students.index', [
            'students'            => $students,
            'kategoriler'         => [],
            'selectedKategori'    => '',
            'basilacakKartSayisi' => Student::whereIn('durum', ['Sicil Oluşturuldu','Sicil Oluştu - Tahakkuk Girildi'])->count(),
        ]);
    }

    /**
     * Basılmış Kartlar (esnek filtre: 'Kart Basıldı' / 'Kart Basildi' / 'Kart Bas%')
     */
    public function basilanKartlar(Request $request)
    {
        $students = Student::query()
            ->where(function($q){
                $q->where('durum', 'Kart Basıldı')
                  ->orWhere('durum', 'Kart Basildi')
                  ->orWhere('durum', 'like', 'Kart Bas%');
            })
            ->orderByDesc('created_at')
            ->paginate(15)
            ->withQueryString();

        return view('admin.students.index', [
            'students'            => $students,
            'kategoriler'         => [],
            'selectedKategori'    => '',
            'basilacakKartSayisi' => Student::whereIn('durum', ['Sicil Oluşturuldu','Sicil Oluştu - Tahakkuk Girildi'])->count(),
        ]);
    }

    /**
     * Sicil Oluşturulanlar (basıma adaylar)
     */
    public function sicilOlusturulanlar()
    {
        $students = Student::whereIn('durum', ['Sicil Oluşturuldu', 'Sicil Oluştu - Tahakkuk Girildi'])
            ->orderByDesc('created_at')
            ->paginate(15);

        return view('admin.students.index', [
            'students'            => $students,
            'kategoriler'         => [],
            'selectedKategori'    => '',
            'basilacakKartSayisi' => Student::whereIn('durum', ['Sicil Oluşturuldu','Sicil Oluştu - Tahakkuk Girildi'])->count(),
        ]);
    }

    /**
     * Eksik Belge Olanlar
     */
    public function eksikBelgeOlanlar()
    {
        $students = Student::where('durum', 'Eksik Belge')
            ->latest()->paginate(15);

        return view('admin.students.index', [
            'students'            => $students,
            'kategoriler'         => [],
            'selectedKategori'    => '',
            'basilacakKartSayisi' => Student::whereIn('durum', ['Sicil Oluşturuldu','Sicil Oluştu - Tahakkuk Girildi'])->count(),
        ]);
    }
}
