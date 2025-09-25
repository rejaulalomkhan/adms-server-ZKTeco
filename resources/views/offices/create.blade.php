@extends('layouts.app')

@section('content')
<h4 class="mb-3">Create Office</h4>

<form method="POST" action="{{ route('offices.store') }}" class="row g-3">
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
        <label class="form-label">Area</label>
        <select name="area_id" class="form-select">
            <option value="">-- None --</option>
            @foreach($areas as $a)
                <option value="{{ $a->id }}">{{ $a->name }}</option>
            @endforeach
        </select>
    </div>
    <div class="col-12">
        <button type="submit" class="btn btn-primary">Save</button>
        <a href="{{ route('offices.index') }}" class="btn btn-secondary">Cancel</a>
    </div>
</form>
@endsection


