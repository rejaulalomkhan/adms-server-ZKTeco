@extends('layouts.app')

@section('content')
<h4 class="mb-3">Edit Device</h4>

<form method="POST" action="{{ route('devices.update', $device->id) }}" class="row g-3">
    @csrf
    @method('PUT')
    <div class="col-md-6">
        <label class="form-label">Name (optional)</label>
        <input type="text" name="nama" class="form-control" value="{{ $device->nama }}">
    </div>
    <div class="col-md-6">
        <label class="form-label">Serial Number (SN)</label>
        <input type="text" name="no_sn" class="form-control" required value="{{ $device->no_sn }}">
    </div>
    <div class="col-md-6">
        <label class="form-label">Location (optional)</label>
        <input type="text" name="lokasi" class="form-control" value="{{ $device->lokasi }}">
    </div>
    <div class="col-md-6">
        <label class="form-label">Office (optional)</label>
        <select name="office_id" class="form-select">
            <option value="">-- None --</option>
            @foreach($offices as $o)
                <option value="{{ $o->id }}" {{ $device->office_id == $o->id ? 'selected' : '' }}>{{ $o->name }}</option>
            @endforeach
        </select>
    </div>
    <div class="col-12">
        <button type="submit" class="btn btn-primary">Update</button>
        <a href="{{ route('devices.index') }}" class="btn btn-secondary">Cancel</a>
    </div>
</form>

<form method="POST" action="{{ route('devices.destroy', $device->id) }}" class="mt-3">
    @csrf
    @method('DELETE')
    <button type="submit" class="btn btn-danger" onclick="return confirm('Delete this device?')">Delete</button>
    </form>
@endsection

@extends('layouts.app')

@section('content')
    <div class="container">
        <h2>Edit Device</h2>
        <form method="post" action="{{ route('devices.update', $device->id) }}">
            @csrf
            @method('put')
            <div class="form-group">
                <label for="nama">Nama</label>
                <input type="text" name="nama" class="form-control" id="nama" value="{{ $device->nama }}">
            </div>
            <div class="form-group">
                <label for="no_sn">Nomor Serial</label>
                <input type="text" name="no_sn" class="form-control" id="no_sn" value="{{ $device->no_sn }}">
            </div>
            <div class="form-group">
                <label for="lokasi">Lokasi</label>
                <input type="text" name="lokasi" class="form-control" id="lokasi" value="{{ $device->lokasi }}">
            </div>
            <div class="form-group">
                <label for="online">Online</label>
                <input type="text" name="online" class="form-control" id="online" value="{{ $device->online }}">
            </div>
            <button type="submit" class="btn btn-primary">Update</button>
        </form>
    </div>
@endsection
