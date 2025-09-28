@extends('layouts.app')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
  <h4 class="mb-0">Permissions</h4>
  <a href="{{ route('permissions.create') }}" class="btn btn-primary">Create Permission</a>
  </div>

@if(session('success'))
  <div class="alert alert-success">{{ session('success') }}</div>
@endif

<div class="table-responsive">
  <table class="table table-striped">
    <thead>
      <tr>
        <th>Name</th>
      </tr>
    </thead>
    <tbody>
      @foreach($permissions as $p)
      <tr>
        <td>{{ $p->name }}</td>
      </tr>
      @endforeach
    </tbody>
  </table>
</div>
@endsection


