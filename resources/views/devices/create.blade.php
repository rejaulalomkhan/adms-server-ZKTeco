@extends('layouts.app')

@section('content')
<h4 class="mb-3">Add Device</h4>

<form method="POST" action="{{ route('devices.store') }}" class="row g-3">
    @csrf
    <div class="col-md-6">
        <label class="form-label">Name (optional)</label>
        <input type="text" name="nama" class="form-control">
    </div>
    <div class="col-md-6">
        <label class="form-label">Serial Number (SN)</label>
        <input type="text" name="no_sn" class="form-control" required>
    </div>
    <div class="col-md-6">
        <label class="form-label">Location (optional)</label>
        <input type="text" name="lokasi" class="form-control">
    </div>
    <div class="col-md-6">
        <label class="form-label">Office (optional)</label>
        <select name="office_id" class="form-select">
            <option value="">-- None --</option>
            @foreach($offices as $o)
                <option value="{{ $o->id }}">{{ $o->name }}</option>
            @endforeach
        </select>
    </div>
    <div class="col-12">
        <button type="submit" class="btn btn-primary">Save</button>
        <a href="{{ route('devices.index') }}" class="btn btn-secondary">Cancel</a>
    </div>
</form>
@endsection

@extends('layouts.app')

@section('content')
    <div class="container">
        <h2>Tambah Device</h2>
        <form method="post" action="{{ route('devices.store') }}">
            @csrf
            <div class="form-group">
                <label for="nama">Nama</label>
                <input type="text" name="nama" class="form-control" id="nama" placeholder="Nama">
            </div>
            <div class="form-group">
                <label for="no_sn">Nomor Serial</label>
                <input type="text" name="no_sn" class="form-control" id="no_sn" placeholder="Nomor Serial">
            </div>
            <div class="form-group">
                <label for="lokasi">Lokasi</label>
                <input type="text" name="lokasi" class="form-control" id="lokasi" placeholder="Lokasi">
            </div>

            <button type="submit" class="btn btn-primary">Simpan</button>
        </form>
    </div>
@endsection
