@extends('layouts.app')

@section('content')
<h4 class="mb-3">Edit Area</h4>

<form method="POST" action="{{ route('areas.update', $area->id) }}" class="row g-3">
    @csrf
    @method('PUT')
    <div class="col-md-6">
        <label class="form-label">Name</label>
        <input type="text" name="name" class="form-control" required value="{{ $area->name }}">
    </div>
    <div class="col-md-6">
        <label class="form-label">Parent</label>
        <select name="parent_id" class="form-select">
            <option value="">-- None --</option>
            @foreach($areas as $a)
                <option value="{{ $a->id }}" {{ $area->parent_id == $a->id ? 'selected' : '' }}>{{ $a->name }}</option>
            @endforeach
        </select>
    </div>
    <div class="col-12">
        <button type="submit" class="btn btn-primary">Update</button>
        <a href="{{ route('areas.index') }}" class="btn btn-secondary">Cancel</a>
    </div>
</form>

<form method="POST" action="{{ route('areas.destroy', $area->id) }}" class="mt-3">
    @csrf
    @method('DELETE')
    <button type="submit" class="btn btn-danger" onclick="return confirm('Delete this area?')">Delete</button>
    </form>
@endsection


