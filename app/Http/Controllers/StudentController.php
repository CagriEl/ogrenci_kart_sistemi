<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Models\Student;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Mail;
// use App\Mail\SicilOlusturulduMail;
use App\Mail\KartBasildiMail;
use Illuminate\Support\Str;

class StudentController extends Controller
{
    // Form verilerini kaydeden metot
    public function store(Request $request)
    {
        // Validasyon kuralları
        $request->validate([
            'ad_soyad' => 'required|string|max:255',
            'tc' => 'required|digits:11|unique:students',
            'telefon' => 'required|digits_between:10,11', // Telefon numarasını 10 veya 11 hane ile sınırla
            'adres' => 'required|string|max:700',
            'bolum' => 'required|string|max:255',
            'ogrenci_belgesi' => 'nullable|file|mimes:pdf|max:2048',
            'kimlik_on' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'kimlik_arka' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'vesikalik' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'dogum_tarihi' => [
                'required',
                'date',
                'before:today', // Tarih bugünden önce olmalı
                function ($attribute, $value, $fail) {
                    $year = explode('-', $value)[0];
                    if (strlen($year) !== 4 || !ctype_digit($year)) {
                        $fail('Doğum tarihi geçersiz. Yıl kısmı 4 haneli olmalıdır.');
                    }
                },
            ],
            'baba_adi' => 'required|string|max:255',
            'dogum_yeri' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'aydinlatma_onay' => 'accepted',
        ]);
    
        // 18 yaş kontrolü
        $dogumTarihi = new \DateTime($request->dogum_tarihi);
        $today = new \DateTime();
        $age = $today->diff($dogumTarihi)->y;
    
        if ($age < 18) {
            return redirect()->back()->withErrors(['dogum_tarihi' => '18 yaşından küçükler başvuru yapamaz.']);
        }
    
        // Dosya Yükleme ve Veritabanı Kayıt İşlemleri
        $data = $request->all();
        $data['aydinlatma_onay'] = $request->has('aydinlatma_onay') ? true : false;
    
       

        function sanitizeFileName($filename)
        {
            // Türkçe karakterleri dönüştürme
            $replacements = [
                'ç' => 'c', 'ğ' => 'g', 'ı' => 'i', 'İ' => 'I',
                'ö' => 'o', 'ş' => 's', 'ü' => 'u', 'Ç' => 'C',
                'Ğ' => 'G', 'Ö' => 'O', 'Ş' => 'S', 'Ü' => 'U',
            ];
        
            $sanitized = strtr($filename, $replacements);
            
            // Diğer özel karakterleri kaldırma ve dosya adını kısa tutma
            $sanitized = Str::slug($sanitized);
        
            return $sanitized;
        }
        
        // Örnek kullanım
        if ($request->hasFile('vesikalik')) {
            $originalName = pathinfo($request->file('vesikalik')->getClientOriginalName(), PATHINFO_FILENAME);
            $sanitizedFilename = sanitizeFileName($originalName);
            $filename = Str::limit($sanitizedFilename, 10, '') . '_' . Str::uuid() . '.' . $request->file('vesikalik')->getClientOriginalExtension();
            $data['vesikalik'] = $request->file('vesikalik')->storeAs('vesikalik_fotograflar', $filename, 'public');
        }
        
        // Diğer dosyalar için de aynı işlemi uygulayın
        if ($request->hasFile('kimlik_on')) {
            $originalName = pathinfo($request->file('kimlik_on')->getClientOriginalName(), PATHINFO_FILENAME);
            $sanitizedFilename = sanitizeFileName($originalName);
            $filename = Str::limit($sanitizedFilename, 10, '') . '_' . Str::uuid() . '.' . $request->file('kimlik_on')->getClientOriginalExtension();
            $data['kimlik_on'] = $request->file('kimlik_on')->storeAs('kimlik_fotograflar', $filename, 'public');
        }
        
        if ($request->hasFile('kimlik_arka')) {
            $originalName = pathinfo($request->file('kimlik_arka')->getClientOriginalName(), PATHINFO_FILENAME);
            $sanitizedFilename = sanitizeFileName($originalName);
            $filename = Str::limit($sanitizedFilename, 10, '') . '_' . Str::uuid() . '.' . $request->file('kimlik_arka')->getClientOriginalExtension();
            $data['kimlik_arka'] = $request->file('kimlik_arka')->storeAs('kimlik_fotograflar', $filename, 'public');
        }
        
        if ($request->hasFile('ogrenci_belgesi')) {
            $originalName = pathinfo($request->file('ogrenci_belgesi')->getClientOriginalName(), PATHINFO_FILENAME);
            $sanitizedFilename = sanitizeFileName($originalName);
            $filename = Str::limit($sanitizedFilename, 10, '') . '_' . Str::uuid() . '.' . $request->file('ogrenci_belgesi')->getClientOriginalExtension();
            $data['ogrenci_belgesi'] = $request->file('ogrenci_belgesi')->storeAs('ogrenci_belgeleri', $filename, 'public');
        }
        
        
        try{    
        // Veritabanına Kayıt
        Student::create($data);
    
        return redirect()->back()->with('success', 'Başvurunuz başarıyla alındı.');
    } catch (\Exception $e) {
        return redirect()->back()->with('error', 'Kayıt sırasında bir hata oluştu: ' . $e->getMessage());
    }    }
    

    // Öğrenci kayıtlarının listelendiği admin paneli
    public function adminIndex()
    {
        $students = Student::where('durum', 'İşlem Bekliyor')->paginate(20);
    $kartBasildiBekleyen = Student::where('durum', 'Sicil Oluştu - Tahakkuk Girildi')->count();

    return view('admin.students.index', compact('students', 'kartBasildiBekleyen'));
    
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
            case 'kimlik_on':
                $filePath = $student->kimlik_on;
                $downloadName = $student->tc . '_' . str_replace(' ', '_', $student->ad_soyad) . '_kimlik_on.' . pathinfo($filePath, PATHINFO_EXTENSION);
                break;
            case 'kimlik_arka':
                $filePath = $student->kimlik_arka;
                $downloadName = $student->tc . '_' . str_replace(' ', '_', $student->ad_soyad) . '_kimlik_arka.' . pathinfo($filePath, PATHINFO_EXTENSION);
                break;
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
    
 
    
    }