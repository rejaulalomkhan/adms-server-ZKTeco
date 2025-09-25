@extends('layouts.app')

@section('content')
<h4 class="mb-3">Edit Holiday</h4>

<form method="POST" action="{{ route('holidays.update', $h->id) }}" class="row g-3">
    @csrf
    @method('PUT')
    <div class="col-md-6">
        <label class="form-label">Name</label>
        <input type="text" name="name" class="form-control" required value="{{ $h->name }}">
    </div>
    <div class="col-md-6">
        <label class="form-label">Date</label>
        <input type="date" name="date" class="form-control" required value="{{ $h->date }}">
    </div>
    <div class="col-md-6 form-check mt-4">
        <input type="checkbox" name="is_recurring" value="1" class="form-check-input" id="recurring" {{ $h->is_recurring ? 'checked' : '' }}>
        <label class="form-check-label" for="recurring">Recurring (every year)</label>
    </div>
    <div class="col-md-6">
        <label class="form-label">Area (optional)</label>
        <select name="area_id" class="form-select">
            <option value="">-- None --</option>
            @foreach($areas as $a)
                <option value="{{ $a->id }}" {{ $h->area_id == $a->id ? 'selected' : '' }}>{{ $a->name }}</option>
            @endforeach
        </select>
    </div>
    <div class="col-md-6">
        <label class="form-label">Office (optional)</label>
        <select name="office_id" class="form-select">
            <option value="">-- None --</option>
            @foreach($offices as $o)
                <option value="{{ $o->id }}" {{ $h->office_id == $o->id ? 'selected' : '' }}>{{ $o->name }}</option>
            @endforeach
        </select>
    </div>
    <div class="col-12">
        <button type="submit" class="btn btn-primary">Update</button>
        <a href="{{ route('holidays.index') }}" class="btn btn-secondary">Cancel</a>
    </div>
</form>

<form method="POST" action="{{ route('holidays.destroy', $h->id) }}" class="mt-3">
    @csrf
    @method('DELETE')
    <button type="submit" class="btn btn-danger" onclick="return confirm('Delete this holiday?')">Delete</button>
    </form>
@endsection


