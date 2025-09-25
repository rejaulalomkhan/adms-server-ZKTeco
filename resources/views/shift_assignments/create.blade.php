@extends('layouts.app')

@section('content')
<h4 class="mb-3">Create Manual Shift Assignment</h4>

<form method="POST" action="{{ route('shift-assignments.store') }}" class="row g-3">
    @csrf
    <div class="col-md-6">
        <label class="form-label">Employee</label>
        <select name="employee_id" class="form-select" required>
            @foreach($users as $u)
                <option value="{{ $u->id }}">{{ $u->name }}</option>
            @endforeach
        </select>
    </div>
    <div class="col-md-6">
        <label class="form-label">Shift</label>
        <select name="shift_id" class="form-select" required>
            @foreach($shifts as $s)
                <option value="{{ $s->id }}">{{ $s->name }}</option>
            @endforeach
        </select>
    </div>
    <div class="col-md-6">
        <label class="form-label">Start Date</label>
        <input type="date" name="start_date" class="form-control" required>
    </div>
    <div class="col-md-6">
        <label class="form-label">End Date</label>
        <input type="date" name="end_date" class="form-control">
    </div>
    <div class="col-md-6">
        <label class="form-label">Priority</label>
        <input type="number" name="priority" class="form-control" min="1" value="1">
    </div>
    <div class="col-md-6">
        <label class="form-label">Reason</label>
        <input type="text" name="reason" class="form-control" maxlength="255">
    </div>
    <div class="col-12">
        <button type="submit" class="btn btn-primary">Save</button>
        <a href="{{ route('shift-assignments.index') }}" class="btn btn-secondary">Cancel</a>
    </div>
</form>
@endsection


