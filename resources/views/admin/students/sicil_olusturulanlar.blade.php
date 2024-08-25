@extends('layouts.app')

@section('content')
<div class="container mt-4">
    <h1>Sicili Oluşturulanlar</h1>

    @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    <table class="table table-bordered table-striped">
        <thead>
            <tr>
                <th>ID</th>
                <th>Ad Soyad</th>
                <th>TC Kimlik No</th>
                <th>Sicil</th>
                <th>Durum</th>
                <th>Kart Durumu (Kart Basıldı İse Güncelleyin)</th>
            </tr>
        </thead>
        <tbody>
            @foreach($students as $student)
                <tr>
                    <td>{{$student->id}}</td>
                    <td>{{ $student->ad_soyad }}</td>
                    <td>{{ $student->tc }}</td>
                    <td>{{ $student->sicil }}</td>
                    <td>{{ $student->durum }}</td>
                    <td>
                        <form action="{{ route('admin.students.update_status', ['id' => $student->id]) }}" method="POST">
                            @csrf
                            <!-- Kart Basıldı Checkbox -->
                            <input type="checkbox" name="kart_basildi" {{ $student->durum == 'Kart Basıldı' ? 'checked' : '' }}>
                            <button type="submit" class="btn btn-primary">Kart Durumu Güncelle</button>
                        </form>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>

    {{ $students->links() }} <!-- Sayfalama için -->
</div>
@endsection
