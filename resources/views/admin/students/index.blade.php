@extends('layouts.app')

@section('content')
<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Paneli - Öğrenci Kayıtları</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .container {
            max-width: 1800px;
            margin: 0 auto;
            padding: 20px;
        }

        .table {
            width: 100%;
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
        @media print {
            .table {
                width: 100%;
                margin-bottom: 1rem;
                color: #212529;
                background-color: transparent;
            }

            .table th, .table td {
                padding: 0.75rem;
                vertical-align: top;
                border-top: 1px solid #dee2e6;
            }

            .table thead th {
                vertical-align: bottom;
                border-bottom: 2px solid #dee2e6;
            }

            .table tbody + tbody {
                border-top: 2px solid #dee2e6;
            }
        }
    </style>
    <script>
        function printAllStudents() {
            var content = '';

            @foreach($students as $student)
                content += `
                    <div>
                        <p><strong>Ad Soyad:</strong> {{ $student->ad_soyad }}</p>
                        <p><strong>TC Kimlik No:</strong> {{ $student->tc }}</p>
                        <p><strong>Baba Adı:</strong> {{ $student->baba_adi }}</p>
                        <p><strong>Doğum Tarihi:</strong> {{ \Carbon\Carbon::parse($student->dogum_tarihi)->format('d/m/Y') }}</p>
                        <p><strong>Doğum Yeri:</strong> {{ $student->dogum_yeri }}</p>
                        <p><strong>Telefon:</strong> {{ $student->telefon }}</p>
                        <p><strong>Adres:</strong> {{ $student->adres }}</p>
                        <p><strong>E-Mail:</strong> {{ $student->email }}</p>
                        <hr>
                    </div>
                `;
            @endforeach

            var printWindow = window.open('', '', 'height=600,width=800');
            printWindow.document.write('<html><head><title>Tüm Öğrenci Kayıtları</title>');
            printWindow.document.write('<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">');
            printWindow.document.write('</head><body>');
            printWindow.document.write(content);
            printWindow.document.write('</body></html>');
            printWindow.document.close();
            printWindow.print();
        }

        function printModalContent(studentId) {
            var content = document.getElementById('modalContent' + studentId).innerHTML;
            var printWindow = window.open('', '', 'height=600,width=800');
            printWindow.document.write('<html><head><title>Öğrenci Detayları</title>');
            printWindow.document.write('<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">');
            printWindow.document.write('</head><body>');
            printWindow.document.write(content);
            printWindow.document.write('</body></html>');
            printWindow.document.close();
            printWindow.print();
        }
    </script>
</head>
<body>

    <div class="container mt-4">
        <h1 class="text-center">Öğrenci Kayıtları</h1>
        <div class="text-center"><button id="refreshButton" class="btn btn-primary align-center" onclick="location.reload();">Yenile</button></div><br>

        <div class="text-center"><button type="button" class="btn btn-warning" onclick="printAllStudents()">Tüm Kayıtları Yazdır</button></div>

    </div>
        @if(session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif
        <div class="d-flex justify-content-center">
            {{ $students->onEachSide(1)->links('pagination::bootstrap-4') }}
        </div>
        <div class="alert alert-danger  text-right" style="position: absolute; top: 10px; right: 10px; z-index: 1000;">
            {{ $kartBasildiBekleyen }} adet basılmayı bekleyen kart var.
        </div>
        <table class="table table-bordered table-striped">
            <thead>
                <tr>
                    <th>Id</th>
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
                    <th>Vesikalık Fotoğraf</th>
                    <th>Sicil</th>
                    <th>Durum</th>
                    <th>Aksiyon</th>
                </tr>
            </thead>
            <tbody>
                @foreach($students as $student)
                    <tr>
                        <td>{{$student->id}}</td>
                        <td>{{ $student->ad_soyad }}</td>
                        <td>{{ $student->tc }}</td>
                        <td>{{ $student->baba_adi }}</td>
                        <td>{{ \Carbon\Carbon::parse($student->dogum_tarihi)->format('d/m/Y') }}</td>
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
                            <button type="button" class="btn btn-primary" onclick="printModalContent({{ $student->id }})">Yazdır</button>
                        </td>
                    </tr>

                    <div class="modal fade" id="viewStudentModal{{ $student->id }}" tabindex="-1" aria-labelledby="viewStudentModalLabel{{ $student->id }}" aria-hidden="true">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="viewStudentModalLabel{{ $student->id }}">Öğrenci Detayları</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>
                                <div class="modal-body" id="modalContent{{ $student->id }}">
                                    <p><strong>Ad Soyad:</strong> {{ $student->ad_soyad }}</p>
                                    <p><strong>TC Kimlik No:</strong> {{ $student->tc }}</p>
                                    <p><strong>Baba Adı:</strong> {{ $student->baba_adi }}</p>
                                    <p><strong>Doğum Tarihi:</strong> {{ \Carbon\Carbon::parse($student->dogum_tarihi)->format('d/m/Y') }}</p>
                                    <p><strong>Doğum Yeri:</strong> {{ $student->dogum_yeri }}</p>
                                    <p><strong>Telefon:</strong> {{ $student->telefon }}</p>
                                    <p><strong>Adres:</strong> {{ $student->adres }}</p>
                                    <p><strong>E-Mail:</strong> {{ $student->email }}</p>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Kapat</button>
                                    <button type="button" class="btn btn-primary" onclick="printModalContent({{ $student->id }})">Yazdır</button>
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
@endsection
