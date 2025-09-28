@extends('layouts.app')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
  <h4 class="mb-0">Roles</h4>
  <a href="{{ route('roles.create') }}" class="btn btn-primary">Create Role</a>
  </div>

@if(session('success'))
  <div class="alert alert-success">{{ session('success') }}</div>
@endif

<div class="table-responsive">
  <table class="table table-striped">
    <thead>
      <tr>
        <th>Name</th>
        <th>Permissions</th>
        <th>Actions</th>
      </tr>
    </thead>
    <tbody>
      @foreach($roles as $role)
      <tr>
        <td>{{ $role->name }}</td>
        <td>{{ $role->permissions_count }}</td>
        <td>
          <a href="{{ route('roles.edit', $role->id) }}" class="btn btn-sm btn-outline-primary">Edit</a>
          <form action="{{ route('roles.destroy', $role->id) }}" method="POST" style="display:inline" onsubmit="return confirm('Delete this role?')">
            @csrf
            @method('DELETE')
            <button type="submit" class="btn btn-sm btn-outline-danger">Delete</button>
          </form>
        </td>
      </tr>
      @endforeach
    </tbody>
  </table>
</div>
@endsection


