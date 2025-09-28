@extends('layouts.app')

@section('content')
<h4 class="mb-3">Create Permission</h4>

<form method="POST" action="{{ route('permissions.store') }}" class="row g-3">
  @csrf
  <div class="col-12">
    <label class="form-label">Permission Name</label>
    <input type="text" name="name" class="form-control" required>
  </div>
  <div class="col-12">
    <button type="submit" class="btn btn-primary">Save</button>
    <a href="{{ route('permissions.index') }}" class="btn btn-secondary">Cancel</a>
  </div>
</form>
@endsection


