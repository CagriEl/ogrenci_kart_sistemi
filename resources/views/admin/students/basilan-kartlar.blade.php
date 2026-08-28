@extends('layouts.app')

@section('content')
<div class="container mt-4">
    <h1 class="mb-4">Basılan Kartlar</h1>

    <!-- Arama Formu ve Toplam Basılan Kart Sayısı -->
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center mb-4">
        <!-- Arama Formu -->
        <form action="{{ route('admin.students.basilan_kartlar') }}" method="GET" class="mb-3 mb-md-0">
            <div class="input-group">
                <input type="text"
                       name="tc"
                       class="form-control"
                       placeholder="TC Kimlik No ile Arama Yapınız"
                       value="{{ request('tc') }}">
                <button type="submit" class="btn btn-primary">Ara</button>
            </div>
        </form>

        <h5 class="text-muted">Toplam Basılan Kart: <strong>{{ $basilanKartlar->total() }}</strong></h5>
    </div>

    @if(($basilanKartlar->total() ?? 0) === 0)
        <div class="alert alert-warning">Aradığınız TC kimlik numarasıyla eşleşen kayıt bulunamadı.</div>
    @else
        <div class="table-responsive">
            <table class="table table-bordered table-striped table-hover align-middle">
                <thead class="table-dark">
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
                            <td>{{ $student->dogum_tarihi ? \Carbon\Carbon::parse($student->dogum_tarihi)->format('d/m/Y') : '-' }}</td>
                            <td>{{ $student->telefon }}</td>
                            <td>{{ $student->adres }}</td>
                            <td>{{ $student->email }}</td>
                            <td>{{ $student->sicil }}</td>
                            <td>
                                <span class="badge bg-success">{{ $student->durum }}</span>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div class="d-flex justify-content-center mt-3">
            {{ $basilanKartlar->onEachSide(1)->links('pagination::bootstrap-4') }}
        </div>
    @endif
</div>

<style>
    .table th, .table td {
        vertical-align: middle;
        font-size: 0.9rem;
    }
    .input-group .form-control {
        max-width: 280px;
    }
    .btn-primary {
        min-width: 90px;
    }
</style>
@endsection
