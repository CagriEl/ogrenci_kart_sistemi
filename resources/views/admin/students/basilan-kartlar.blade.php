@extends('layouts.app')

@section('content')
<div class="container mt-4">
    <h1>Basılan Kartlar</h1>

    <!-- Arama Formu -->
    <form action="{{ route('admin.students.basilan_kartlar') }}" method="GET" class="form-inline">
        <input type="text" name="tc" class="form-control" placeholder="TC Kimlik No ile Arama Yapınız" value="{{ request('tc') }}">
        <button type="submit" class="btn btn-primary ml-2">Ara</button>
    </form>

    @if($basilanKartlar->isEmpty())
        <p>Aradığınız TC kimlik numarasıyla eşleşen kayıt bulunamadı.</p>
    @else
        <table class="table table-bordered table-striped">
            <thead>
                <tr>
                    <th>ID</th>
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
                        <td>{{ $student->id }}</td>
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
    @endif
</div>
@endsection
