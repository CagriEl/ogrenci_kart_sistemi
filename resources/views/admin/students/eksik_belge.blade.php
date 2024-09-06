@extends('layouts.app')

@section('content')
    <div class="container mt-4">
        <h1>Eksik Belge Olan Öğrenciler</h1>

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
                    <th>Red Edilme Sebebi</th>
                    <th>Aksiyon</th> <!-- Sil butonu için yeni bir sütun -->
                </tr>
            </thead>
            <tbody>
                @foreach($eksikBelgeOlanlar as $student)
                    <tr>
                        <td>{{ $student->ad_soyad }}</td>
                        <td>{{ $student->tc }}</td>
                        <td>{{ $student->durum }}</td>
                        <td>
                            <!-- Sil butonu -->
                            <form action="{{ route('admin.students.destroy', $student->id) }}" method="POST" style="display: inline;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger btn-sm">Sil</button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <!-- Sayfalama -->
        {{ $eksikBelgeOlanlar->links() }}
    </div>
@endsection
