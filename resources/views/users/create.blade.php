@extends('layouts.app')

@section('content')
<h4 class="mb-3">Add Employee</h4>

<form method="POST" action="{{ route('users.store') }}" class="row g-3">
    @csrf
    <div class="col-md-6">
        <label class="form-label">Name</label>
        <input type="text" name="name" class="form-control" required>
    </div>
    <div class="col-md-6">
        <label class="form-label">Email</label>
        <input type="email" name="email" class="form-control" required>
    </div>
    <div class="col-md-6">
        <label class="form-label">Password</label>
        <input type="password" name="password" class="form-control" required>
    </div>
    <div class="col-md-6">
        <label class="form-label">Office</label>
        <select name="office_id" class="form-select">
            <option value="">-- None --</option>
            @foreach($offices as $o)
                <option value="{{ $o->id }}">{{ $o->name }}</option>
            @endforeach
        </select>
    </div>
    <div class="col-12">
        <button type="submit" class="btn btn-primary">Save</button>
        <a href="{{ route('users.index') }}" class="btn btn-secondary">Cancel</a>
    </div>
</form>
@endsection



