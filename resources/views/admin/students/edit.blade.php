@extends('layouts.app') 
@section('title', 'Başvurular')

@section('content')
     <div class="container mt-4">
        <h1>Öğrenci Kaydı Düzenle</h1>

        <form action="{{ route('admin.students.update', $student->id) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="mb-3">
                <label for="sicil" class="form-label">Sicil Numarası</label>
                <input type="text" class="form-control" id="sicil" name="sicil" value="{{ $student->sicil }}">
            </div>
        
            <div class="mb-3">
                <label for="durum" class="form-label">Durum</label>
                <select class="form-control" id="durum" name="durum">
                    <option value="İşlem Bekliyor" {{ $student->durum == 'İşlem Bekliyor' ? 'selected' : '' }}>İşlem Bekliyor</option>
                    <option value="Sicil Oluştu - Tahakkuk Girildi" {{ $student->durum == 'Sicil Oluştu - Tahakkuk Girildi' ? 'selected' : '' }}>Sicil Oluştu - Tahakkuk Girildi</option>
                    <option value="Kart Basıldı" {{ $student->durum == 'Kart Basıldı' ? 'selected' : '' }}>Kart Basıldı</option>            
                </select>
            </div>
        
            <button type="submit" class="btn btn-primary">Kaydet</button>
        </form>
    </div>
@endsection

