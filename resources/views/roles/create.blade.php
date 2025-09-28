@extends('layouts.app')

@section('content')
<h4 class="mb-3">Create Role</h4>

<form method="POST" action="{{ route('roles.store') }}" class="row g-3">
  @csrf
  <div class="col-12">
    <label class="form-label">Role Name</label>
    <input type="text" name="name" class="form-control" required>
  </div>
  <div class="col-12">
    <label class="form-label">Permissions</label>
    <div class="row">
      @foreach($permissions as $p)
      <div class="col-12 col-md-4">
        <div class="form-check">
          <input class="form-check-input" type="checkbox" name="permissions[]" value="{{ $p->name }}" id="perm-{{ $p->id }}">
          <label class="form-check-label" for="perm-{{ $p->id }}">{{ $p->name }}</label>
        </div>
      </div>
      @endforeach
    </div>
  </div>
  <div class="col-12">
    <button type="submit" class="btn btn-primary">Save</button>
    <a href="{{ route('roles.index') }}" class="btn btn-secondary">Cancel</a>
  </div>
</form>
@endsection


