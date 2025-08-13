<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Models\Student;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Mail;
// use App\Mail\SicilOlusturulduMail;
use App\Mail\KartBasildiMail;
use App\Mail\EksikBelgeMail;
use Illuminate\Support\Str;

class StudentController extends Controller
{
    // Form verilerini kaydeden metot
   public function store(Request $request)
{
    // Temel alanlar: tüm kategorilerde var
    $rules = [
        'kategori'       => 'required|string',
        'ad_soyad'       => 'required|string|max:255',
        'tc'             => 'required|digits:11|unique:applications,tc',
        'baba_adi'       => 'required|string|max:255',
        'dogum_tarihi'   => 'required|date|before:today',
        'telefon'        => 'required|digits_between:10,11',
        'dogum_yeri'     => 'required|string|max:255',
        'adres'          => 'required|string|max:1000',
        'email'          => 'required|email|max:255',
        'bolum'          => 'nullable|string|max:255',

        // Dosyalar (formlarda kullandığın isimlerle birebir)
        'ogrenci_belgesi' => 'nullable|file|mimes:pdf|max:10240',
        'belediye_yazi'   => 'nullable|file|mimes:pdf|max:10240',  // Emniyet/Jandarma/Belediye/Gazi/Sehit
        'vesikalik'       => 'nullable|image|mimes:jpeg,png,jpg|max:5120',
        // 'kimlik_on'       => 'nullable|file|mimes:jpeg,png,jpg,pdf|max:10240',
        // 'kimlik_arka'     => 'nullable|file|mimes:jpeg,png,jpg,pdf|max:10240',

'aydinlatma_onay' => 'accepted',
    ];

    // Kategoriye özel zorunluluklar (istersen aç)
    // if ($request->kategori === 'Ogrenci')  $rules['ogrenci_belgesi'] = 'required|file|mimes:pdf|max:10240';
    // if (in_array($request->kategori, ['Emniyet','Jandarma','Belediye','Gazi','Sehit'])) $rules['belediye_yazi'] = 'required|file|mimes:pdf|max:10240';

    $validated = $request->validate($rules);

    // Bilinen kolonları çek
    $knownKeys = array_keys($rules);
    $knownKeys = array_merge($knownKeys, ['durum','sicil']);
    $data = $request->only($knownKeys);

    // Dosya yükle
    foreach (['ogrenci_belgesi','belediye_yazi','vesikalik','kimlik_on','kimlik_arka'] as $fileKey) {
        if ($request->hasFile($fileKey)) {
            $dir = match ($fileKey) {
                'ogrenci_belgesi' => 'ogrenci_belgeleri',
                'vesikalik'       => 'vesikalik',
                default           => 'ekler',
            };
            $data[$fileKey] = $request->file($fileKey)->store($dir, 'public');
        }
    }

    // Geri kalan tüm alanları meta'ya koy
    $exclude = array_merge($knownKeys, ['_token']);
    $meta = collect($request->all())
        ->reject(fn($v, $k) => in_array($k, $exclude, true))
        ->toArray();

    $data['aydinlatma_onay'] = $request->boolean('aydinlatma_onay');
    $data['meta'] = $meta;

    Student::create($data);

    return back()->with('success', 'Başvurunuz başarıyla alındı. Lütfen mailinizi takip ediniz.');
}

    
    // Öğrenci kayıtlarının listelendiği admin paneli
    public function adminIndex(Request $request)
{
    $query = Student::query();

    // Mevcut mantığın varsa koru: örn. "İşlem Bekliyor"
    $query->where('durum', 'İşlem Bekliyor');

    // Kategori filtresi (?kategori=Ogretmen vb.)
    if ($request->filled('kategori')) {
        $query->where('kategori', $request->string('kategori'));
    }

    $students = $query->orderByDesc('id')->paginate(20);

    $kartBasildiBekleyen = Student::where('durum', 'Sicil Oluştu - Tahakkuk Girildi')->count();

    $kategoriler = [
        'Ogrenci','Ogretmen','Belediye','Emniyet','Jandarma','Gazi','Sehit',
        '65 Yas Ustu','Engelli','Engelli Refakatci','Posta','Annekart','Sari Basin','Zabita'
    ];


{
    // Kategoride sadece Öğrenciler başta gelecek şekilde sıralama
    $students = Student::orderByRaw("CASE WHEN kategori = 'Ogrenci' THEN 0 ELSE 1 END")
        ->paginate(10);

    // Basılacak kart sayısını bul (sicil oluşturulmuş ama kart basılmamış olanlar)
    $basilecekKartSayisi = Student::where('status', 'sicil_olusturuldu')->count();

    return view('admin.students.index', compact('students', 'basilecekKartSayisi'));
}
    return view('admin.students.index', compact('students', 'kartBasildiBekleyen', 'kategoriler'));
}



    public function basilanKartlar(Request $request)
    {
        // Sorguyu başlat
        $query = Student::where('durum', 'Kart Basıldı');
    
        // Eğer bir TC kimlik numarası girilmişse, sorguyu buna göre filtrele
        if ($request->has('tc') && !empty($request->tc)) {
            $query->where('tc', $request->tc);
        }
    
        // Sonuçları sayfalandırarak al
        $basilanKartlar = $query->paginate(20);
    
        // Görünümü döndür
        return view('admin.students.basilan-kartlar', compact('basilanKartlar'));
    }
    
    

    // Öğrenci kaydının düzenlendiği metot
    public function edit(Student $student)
    {
        return view('admin.students.edit', compact('student'));
    }

    public function update(Request $request, Student $student)
{
    $request->validate([
        'sicil' => 'nullable|string|max:255',
        'durum' => 'required|string',
    ]);

    $student->sicil = $request->input('sicil');
    $student->durum = $request->input('durum');
    $student->save();

    // Sicil numarası oluşturulduğunda e-posta gönder
    // if ($student->durum == 'Sicil Oluştu - Tahakkuk Girildi') {
    //     Mail::to($student->email)->send(new SicilOlusturulduMail($student));
    // }

    if ($student->durum == 'Kart Basıldı') {
        Mail::to($student->email)->send(new KartBasildiMail($student));
    }
    return redirect()->route('admin.students.index')->with('success', 'Öğrenci kaydı başarıyla güncellendi.');
}
    
    // Dosya indirme işlemi
    public function downloadFile($id, $file_type)
    {
        $student = Student::findOrFail($id);

        switch ($file_type) {
            // case 'kimlik_on':
            //     $filePath = $student->kimlik_on;
            //     $downloadName = $student->tc . '_' . str_replace(' ', '_', $student->ad_soyad) . '_kimlik_on.' . pathinfo($filePath, PATHINFO_EXTENSION);
            //     break;
            // case 'kimlik_arka':
            //     $filePath = $student->kimlik_arka;
            //     $downloadName = $student->tc . '_' . str_replace(' ', '_', $student->ad_soyad) . '_kimlik_arka.' . pathinfo($filePath, PATHINFO_EXTENSION);
            //     break;
            case 'vesikalik':
                $filePath = $student->vesikalik;
                $downloadName = $student->tc . '_' . str_replace(' ', '_', $student->ad_soyad) . '_vesikalik.' . pathinfo($filePath, PATHINFO_EXTENSION);
                break;
            case 'ogrenci_belgesi':
                $filePath = $student->ogrenci_belgesi;
                $downloadName = $student->tc . '_' . str_replace(' ', '_', $student->ad_soyad) . '_ogrenci_belgesi.' . pathinfo($filePath, PATHINFO_EXTENSION);
                break;
            default:
                abort(404);
        }

        if ($filePath && Storage::disk('public')->exists($filePath)) {
            $file = Storage::disk('public')->get($filePath);
            $mimeType = Storage::disk('public')->mimeType($filePath);
    
            return response($file, 200)
                ->header('Content-Type', $mimeType)
                ->header('Content-Disposition', 'attachment; filename="'.$downloadName.'"');
        } else {
            abort(404, 'Dosya bulunamadı.');
        }
    
    }

    public function sicilOlusturulanlar()
    {
        $students = Student::where('durum', 'Sicil Oluştu - Tahakkuk Girildi')->paginate(20);
        return view('admin.students.sicil_olusturulanlar', compact('students'));
    }

    public function destroy(Student $student)
    {
        $student->delete();
        return redirect()->route('admin.students.index')->with('success', 'Kayıt başarıyla silindi.');
    }
    public function updateStatus(Request $request, $id)
    {
        // Mevcut öğrenci kaydını al
        $student = Student::findOrFail($id);
    
        // Eğer kart_basildi checkbox'ı işaretliyse durumu "Kart Basıldı" yap ve mail gönder
        if ($request->has('kart_basildi')) {
            $student->durum = 'Kart Basıldı';
    
            // Kart Basıldı mailini gönder
            Mail::to($student->email)->send(new KartBasildiMail($student));
        } else {
            // Eğer durum değişikliği "İşlem Bekliyor" ise ve sicil numarası atanmışsa bu işlemi engelle
            if ($student->sicil && $request->input('durum') == 'İşlem Bekliyor') {
                return redirect()->back()->with('error', 'Sicil numarası atanmış bir kaydı "İşlem Bekliyor" durumuna geri alamazsınız.');
            }
    
            $student->durum = $request->input('durum');
        }
    
        // Kaydı güncelle
        $student->save();
    
        return redirect()->back()->with('success', 'Durum başarıyla güncellendi.');
    }
    
    public function sendEksikBelgeMail(Request $request, $id)
    {
        // Öğrenci kaydını al
        $student = Student::findOrFail($id);
        
        // Eksik belge mailini gönder
        Mail::to($student->email)->send(new EksikBelgeMail($student, $request->aciklama));
    
        // Öğrencinin durumunu "Eksik Belge" olarak güncelle
        $student->durum = 'Eksik Belge - ' . $request->aciklama;
        $student->save();
    
        // Eksik Belge sayfasına yönlendir
        return redirect()->route('admin.students.index')->with('success', 'Eksik belge maili gönderildi ve öğrenci kaydı "Eksik Belge" olarak işaretlendi.');
    }
    
    public function showEksikBelge()
    {
        $students = Student::where('durum', 'Eksik Belge')->paginate(20);
        return view('admin.students.eksik_belge', compact('students'));
    }
    public function eksikBelgeOlanlar()
    {
        // Durumu 'Eksik Belge' olan kayıtları çeker
        $eksikBelgeOlanlar = Student::where('durum', 'like', 'Eksik Belge%')->paginate(20);
        
        // Görünüm dosyasına eksik belge olanları gönder
        return view('admin.students.eksik_belge', compact('eksikBelgeOlanlar'));
    }
    
    
    }