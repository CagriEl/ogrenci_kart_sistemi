@extends('layouts.app')
@section('content')
<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Basılan Kartlar - Öğrenci Kayıtları</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .container {
            max-width: 1200px;
            margin: 0 auto;
        }
    </style>
</head>
<body>
    <div class="container mt-4">
        <h1>Basılan Kartlar</h1>

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
                    <th>Doğum Tarihi</th>
                    <th>Telefon</th>
                    <th>Adres</th>
                    <th>E-Mail</th>
                    <th>Sicil</th>
                    <th>Durum</th>
                </tr>
            </thead>
            <tbody>
                @foreach($basilanKartlar as $student)
                    <tr>
                        <td>{{ $student->ad_soyad }}</td>
                        <td>{{ $student->tc }}</td>
                        <td>{{ \Carbon\Carbon::parse($student->dogum_tarihi)->format('d/m/Y') }}</td>
                        <td>{{ $student->telefon }}</td>
                        <td>{{ $student->adres }}</td>
                        <td>{{ $student->email }}</td>
                        <td>{{ $student->sicil }}</td>
                        <td>{{ $student->durum }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <!-- Sayfalama -->
        {{ $basilanKartlar->links() }}
    </div>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
@endsection
