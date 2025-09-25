@extends('layouts.app')

@section('content')
<h4 class="mb-3">Create Shift</h4>

<form method="POST" action="{{ route('shifts.store') }}" class="row g-3">
    @csrf
    <div class="col-md-6">
        <label class="form-label">Name</label>
        <input type="text" name="name" class="form-control" required>
    </div>
    <div class="col-md-6">
        <label class="form-label">Code</label>
        <input type="text" name="code" class="form-control">
    </div>
    <div class="col-md-6">
        <label class="form-label">Start Time</label>
        <input type="time" name="start_time" class="form-control" required>
    </div>
    <div class="col-md-6">
        <label class="form-label">End Time</label>
        <input type="time" name="end_time" class="form-control" required>
    </div>
    <div class="col-md-6">
        <label class="form-label">Break Minutes</label>
        <input type="number" name="break_minutes" class="form-control" value="0" min="0">
    </div>
    <div class="col-md-6">
        <label class="form-label">Grace Minutes</label>
        <input type="number" name="grace_minutes" class="form-control" value="0" min="0">
    </div>
    <div class="col-md-6">
        <label class="form-label">Expected Hours</label>
        <input type="number" name="expected_hours" class="form-control" min="0" max="24">
    </div>
    <div class="col-md-6 form-check mt-4">
        <input type="checkbox" name="is_overnight" value="1" class="form-check-input" id="overnight">
        <label class="form-check-label" for="overnight">Overnight</label>
    </div>
    <div class="col-md-6 form-check mt-4">
        <input type="checkbox" name="active" value="1" class="form-check-input" id="active" checked>
        <label class="form-check-label" for="active">Active</label>
    </div>
    <div class="col-12">
        <button type="submit" class="btn btn-primary">Save</button>
        <a href="{{ route('shifts.index') }}" class="btn btn-secondary">Cancel</a>
    </div>
</form>
@endsection


