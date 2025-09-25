@extends('layouts.app')

@section('content')
<h4 class="mb-3">Edit Shift</h4>

<form method="POST" action="{{ route('shifts.update', $shift->id) }}" class="row g-3">
    @csrf
    @method('PUT')
    <div class="col-md-6">
        <label class="form-label">Name</label>
        <input type="text" name="name" class="form-control" required value="{{ $shift->name }}">
    </div>
    <div class="col-md-6">
        <label class="form-label">Code</label>
        <input type="text" name="code" class="form-control" value="{{ $shift->code }}">
    </div>
    <div class="col-md-6">
        <label class="form-label">Start Time</label>
        <input type="time" name="start_time" class="form-control" required value="{{ $shift->start_time }}">
    </div>
    <div class="col-md-6">
        <label class="form-label">End Time</label>
        <input type="time" name="end_time" class="form-control" required value="{{ $shift->end_time }}">
    </div>
    <div class="col-md-6">
        <label class="form-label">Break Minutes</label>
        <input type="number" name="break_minutes" class="form-control" value="{{ $shift->break_minutes }}" min="0">
    </div>
    <div class="col-md-6">
        <label class="form-label">Grace Minutes</label>
        <input type="number" name="grace_minutes" class="form-control" value="{{ $shift->grace_minutes }}" min="0">
    </div>
    <div class="col-md-6">
        <label class="form-label">Expected Hours</label>
        <input type="number" name="expected_hours" class="form-control" min="0" max="24" value="{{ $shift->expected_hours }}">
    </div>
    <div class="col-md-6 form-check mt-4">
        <input type="checkbox" name="is_overnight" value="1" class="form-check-input" id="overnight" {{ $shift->is_overnight ? 'checked' : '' }}>
        <label class="form-check-label" for="overnight">Overnight</label>
    </div>
    <div class="col-md-6 form-check mt-4">
        <input type="checkbox" name="active" value="1" class="form-check-input" id="active" {{ $shift->active ? 'checked' : '' }}>
        <label class="form-check-label" for="active">Active</label>
    </div>
    <div class="col-12">
        <button type="submit" class="btn btn-primary">Update</button>
        <a href="{{ route('shifts.index') }}" class="btn btn-secondary">Cancel</a>
    </div>
</form>

<form method="POST" action="{{ route('shifts.destroy', $shift->id) }}" class="mt-3">
    @csrf
    @method('DELETE')
    <button type="submit" class="btn btn-danger" onclick="return confirm('Delete this shift?')">Delete</button>
</form>
@endsection


