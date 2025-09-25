@extends('layouts.app')

@section('content')
<h4 class="mb-3">Edit Manual Shift Assignment</h4>

<form method="POST" action="{{ route('shift-assignments.update', $assignment->id) }}" class="row g-3">
    @csrf
    @method('PUT')
    <div class="col-md-6">
        <label class="form-label">Employee</label>
        <select name="employee_id" class="form-select" required>
            @foreach($users as $u)
                <option value="{{ $u->id }}" {{ $assignment->employee_id == $u->id ? 'selected' : '' }}>{{ $u->name }}</option>
            @endforeach
        </select>
    </div>
    <div class="col-md-6">
        <label class="form-label">Shift</label>
        <select name="shift_id" class="form-select" required>
            @foreach($shifts as $s)
                <option value="{{ $s->id }}" {{ $assignment->shift_id == $s->id ? 'selected' : '' }}>{{ $s->name }}</option>
            @endforeach
        </select>
    </div>
    <div class="col-md-6">
        <label class="form-label">Start Date</label>
        <input type="date" name="start_date" class="form-control" required value="{{ $assignment->start_date }}">
    </div>
    <div class="col-md-6">
        <label class="form-label">End Date</label>
        <input type="date" name="end_date" class="form-control" value="{{ $assignment->end_date }}">
    </div>
    <div class="col-md-6">
        <label class="form-label">Priority</label>
        <input type="number" name="priority" class="form-control" min="1" value="{{ $assignment->priority }}">
    </div>
    <div class="col-md-6">
        <label class="form-label">Reason</label>
        <input type="text" name="reason" class="form-control" maxlength="255" value="{{ $assignment->reason }}">
    </div>
    <div class="col-12">
        <button type="submit" class="btn btn-primary">Update</button>
        <a href="{{ route('shift-assignments.index') }}" class="btn btn-secondary">Cancel</a>
    </div>
</form>

<form method="POST" action="{{ route('shift-assignments.destroy', $assignment->id) }}" class="mt-3">
    @csrf
    @method('DELETE')
    <button type="submit" class="btn btn-danger" onclick="return confirm('Delete this assignment?')">Delete</button>
    </form>
@endsection


