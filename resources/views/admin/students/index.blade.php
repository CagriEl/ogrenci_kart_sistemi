<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Paneli - Öğrenci Kayıtları</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .container {
            max-width: 1200px;
            margin: 0 auto;
        }

        .table thead th {
            background-color: #003366;
            color: white;
            text-align: center;
            vertical-align: middle;
        }

        .table tbody td {
            text-align: center;
            vertical-align: middle;
        }

        .navbar-brand {
            font-size: 24px;
            font-weight: bold;
        }

        .btn-sm {
            padding: 5px 10px;
            font-size: 14px;
        }

        .modal-body p {
            margin-bottom: 10px;
        }
    </style>
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <div class="container-fluid">
            <a class="navbar-brand" href="#">Admin Paneli</a>
            <div class="collapse navbar-collapse">
                <ul class="navbar-nav ml-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('logout') }}"
                           onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                            Çıkış Yap
                        </a>
                        <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                            @csrf
                        </form>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <div class="container mt-4">
        <h1>Öğrenci Kayıtları</h1>

        @if(session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif

        <table class="table table-bordered table-striped">
            <thead>
                <tr>
                    <th>Ad Soyad</th>
                    <th>TC Kimlik No</th>
                    <th>Baba Adı</th>
                    <th>Doğum Tarihi</th>
                    <th>Doğum Yeri</th>
                    <th>Telefon</th>
                    <th>Adres</th>
                    <th>E-Mail</th>
                    <th>Bölüm</th>
                    <th>Öğrenci Belgesi</th>
                    <th>Kimlik Ön</th>
                    <th>Kimlik Arka</th>
                    <th>Vesikalık Fotoğraf</th>
                    <th>Sicil</th>
                    <th>Durum</th>
                    <th>Aksiyon</th>
                </tr>
            </thead>
            <tbody>
                @foreach($students as $student)
                    <tr>
                        <td>{{ $student->ad_soyad }}</td>
                        <td>{{ $student->tc }}</td>
                        <td>{{ $student->baba_adi }}</td>
                        <td>{{ $student->dogum_tarihi }}</td>
                        <td>{{ $student->dogum_yeri }}</td>
                        <td>{{ $student->telefon }}</td>
                        <td>{{ $student->adres }}</td>
                        <td>{{ $student->email }}</td>
                        <td>{{ $student->bolum }}</td>
                        <td>
                            @if($student->ogrenci_belgesi)
                                <a href="{{ route('admin.students.download', ['id' => $student->id, 'file_type' => 'ogrenci_belgesi']) }}" class="btn btn-info btn-sm">
                                    PDF İndir
                                </a>
                            @else
                                Yok
                            @endif
                        </td>
                        <td>
                            @if($student->kimlik_on)
                                <a href="{{ route('admin.students.download', ['id' => $student->id, 'file_type' => 'kimlik_on']) }}" class="btn btn-info btn-sm">
                                    Ön Yüz İndir
                                </a>
                            @else
                                Yok
                            @endif
                        </td>
                        <td>
                            @if($student->kimlik_arka)
                                <a href="{{ route('admin.students.download', ['id' => $student->id, 'file_type' => 'kimlik_arka']) }}" class="btn btn-info btn-sm">
                                    Arka Yüz İndir
                                </a>
                            @else
                                Yok
                            @endif
                        </td>
                        <td>
                            @if($student->vesikalik)
                                <a href="{{ route('admin.students.download', ['id' => $student->id, 'file_type' => 'vesikalik']) }}" class="btn btn-info btn-sm">
                                    Vesikalık İndir
                                </a>
                            @else
                                Yok
                            @endif
                        </td>
                        <td>{{ $student->sicil }}</td>
                        <td>{{ $student->durum }}</td>
                        <td>
                            <a href="{{ route('admin.students.edit', $student->id) }}" class="btn btn-warning btn-sm">Düzenle</a>
                            <form action="{{ route('admin.students.destroy', $student->id) }}" method="POST" style="display: inline;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger btn-sm">Sil</button>
                            </form>
                            <button type="button" class="btn btn-info btn-sm" data-bs-toggle="modal" data-bs-target="#viewStudentModal{{ $student->id }}">
                                Görüntüle
                            </button>
                        </td>
                    </tr>
                    <div class="modal fade" id="viewStudentModal{{ $student->id }}" tabindex="-1" aria-labelledby="viewStudentModalLabel{{ $student->id }}" aria-hidden="true">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="viewStudentModalLabel{{ $student->id }}">Öğrenci Detayları</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>
                                <div class="modal-body">
                                    <p><strong>Ad Soyad:</strong> {{ $student->ad_soyad }}</p>
                                    <p><strong>TC Kimlik No:</strong> {{ $student->tc }}</p>
                                    <p><strong>Baba Adı:</strong> {{ $student->baba_adi }}</p>
                                    <p><strong>Doğum Tarihi:</strong> {{ $student->dogum_tarihi }}</p>
                                    <p><strong>Doğum Yeri:</strong> {{ $student->dogum_yeri }}</p>
                                    <p><strong>Telefon:</strong> {{ $student->telefon }}</p>
                                    <p><strong>Adres:</strong> {{ $student->adres }}</p>
                                    <p><strong>E-Mail:</strong> {{ $student->email }}</p>
                                    <p><strong>Bölüm:</strong> {{ $student->bolum }}</p>
                                    <p><strong>Öğrenci Belgesi:</strong> 
                                        @if($student->ogrenci_belgesi)
                                            <a href="{{ Storage::url($student->ogrenci_belgesi) }}" target="_blank">Görüntüle</a>
                                        @else
                                            Yok
                                        @endif
                                    </p>
                                    <p><strong>Kimlik Ön:</strong> 
                                        @if($student->kimlik_on)
                                            <a href="{{ Storage::url($student->kimlik_on) }}" target="_blank">Görüntüle</a>
                                        @else
                                            Yok
                                        @endif
                                    </p>
                                    <p><strong>Kimlik Arka:</strong> 
                                        @if($student->kimlik_arka)
                                            <a href="{{ Storage::url($student->kimlik_arka) }}" target="_blank">Görüntüle</a>
                                        @else
                                            Yok
                                        @endif
                                    </p>
                                    <p><strong>Vesikalık Fotoğraf:</strong> 
                                        @if($student->vesikalik)
                                        <a href="{{ Storage::url($student->vesikalik) }}" target="_blank">Görüntüle</a>
                                    @else
                                        Yok
                                    @endif
                                    </p>
                                    <p><strong>Sicil:</strong> {{ $student->sicil }}</p>
                                    <p><strong>Durum:</strong> {{ $student->durum }}</p>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Kapat</button>
                                </div>                                
                            </div>
                        </div>
                    </div>
                @endforeach
            </tbody>
        </table>
    </div>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
