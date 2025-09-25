@extends('layouts.app')

@section('content')
<h4 class="mb-3">Edit Office</h4>

<form method="POST" action="{{ route('offices.update', $office->id) }}" class="row g-3">
    @csrf
    @method('PUT')
    <div class="col-md-6">
        <label class="form-label">Name</label>
        <input type="text" name="name" class="form-control" required value="{{ $office->name }}">
    </div>
    <div class="col-md-6">
        <label class="form-label">Code</label>
        <input type="text" name="code" class="form-control" value="{{ $office->code }}">
    </div>
    <div class="col-md-6">
        <label class="form-label">Area</label>
        <select name="area_id" class="form-select">
            <option value="">-- None --</option>
            @foreach($areas as $a)
                <option value="{{ $a->id }}" {{ $office->area_id == $a->id ? 'selected' : '' }}>{{ $a->name }}</option>
            @endforeach
        </select>
    </div>
    <div class="col-12">
        <button type="submit" class="btn btn-primary">Update</button>
        <a href="{{ route('offices.index') }}" class="btn btn-secondary">Cancel</a>
    </div>
</form>

<form method="POST" action="{{ route('offices.destroy', $office->id) }}" class="mt-3">
    @csrf
    @method('DELETE')
    <button type="submit" class="btn btn-danger" onclick="return confirm('Delete this office?')">Delete</button>
    </form>
@endsection


