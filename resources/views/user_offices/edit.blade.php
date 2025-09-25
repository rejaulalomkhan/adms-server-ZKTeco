@extends('layouts.app')

@section('content')
<h4 class="mb-3">Assign Office to User</h4>

<form method="POST" action="{{ route('user-offices.update', $user->id) }}" class="row g-3">
    @csrf
    @method('PUT')
    <div class="col-md-6">
        <label class="form-label">User</label>
        <input type="text" class="form-control" value="{{ $user->name }} ({{ $user->email }})" disabled>
    </div>
    <div class="col-md-6">
        <label class="form-label">Office</label>
        <select name="office_id" class="form-select">
            <option value="">-- None --</option>
            @foreach($offices as $o)
                <option value="{{ $o->id }}" {{ $user->office_id == $o->id ? 'selected' : '' }}>{{ $o->name }}</option>
            @endforeach
        </select>
    </div>
    <div class="col-12">
        <button type="submit" class="btn btn-primary">Save</button>
        <a href="{{ route('user-offices.index') }}" class="btn btn-secondary">Cancel</a>
    </div>
</form>
@endsection


