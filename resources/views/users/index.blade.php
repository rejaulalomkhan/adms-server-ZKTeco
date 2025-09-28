@extends('layouts.app')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
    <h4 class="mb-0">Employees</h4>
    <a href="{{ route('users.create') }}" class="btn btn-primary">Add Employee</a>
    </div>

@if(session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
@endif

<div class="table-responsive">
  <table class="table table-striped table-bordered w-100" id="users-table">
    <thead>
      <tr>
        <th>ID</th>
        <th>Name</th>
        <th>Photo</th>
        <th>Email</th>
        <th>Office</th>
        <th>Action</th>
      </tr>
    </thead>
    <tbody>
      @foreach($users as $u)
      <tr>
        <td>{{ $u->id }}</td>
        <td>{{ $u->name }}</td>
        <td>
          @if($u->profile_image)
            <img src="{{ asset('storage/'.$u->profile_image) }}" alt="{{ $u->name }}" class="rounded-circle" width="40" height="40">
          @else
            <img src="https://ui-avatars.com/api/?background=0D8ABC&color=fff&name={{ urlencode($u->name) }}" class="rounded-circle" width="40" height="40" alt="avatar">
          @endif
        </td>
        <td>{{ $u->email }}</td>
        <td>{{ $u->office_name }}</td>
        <td>
          <a href="{{ route('users.edit', $u->id) }}" class="btn btn-sm btn-outline-primary">Edit</a>
          <form action="{{ route('users.destroy', $u->id) }}" method="POST" style="display:inline" onsubmit="return confirm('Delete this employee?')">
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

<script src="https://cdn.datatables.net/responsive/2.4.1/js/dataTables.responsive.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.1/js/dataTables.buttons.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.1/js/buttons.bootstrap5.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.10.1/jszip.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/pdfmake.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/vfs_fonts.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.1/js/buttons.html5.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.1/js/buttons.print.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.1/js/buttons.colVis.min.js"></script>

<link href="https://cdn.datatables.net/responsive/2.4.1/css/responsive.bootstrap5.min.css" rel="stylesheet"/>
<link href="https://cdn.datatables.net/buttons/2.4.1/css/buttons.bootstrap5.min.css" rel="stylesheet"/>

<script>
$(function(){
  $('#users-table').DataTable({
    responsive: true,
    order: [[0,'desc']],
    dom: 'Bfrtip',
    buttons: [
      { extend: 'copy', className: 'btn btn-sm btn-outline-secondary' },
      { extend: 'csv', className: 'btn btn-sm btn-outline-secondary' },
      { extend: 'excel', className: 'btn btn-sm btn-outline-secondary' },
      { extend: 'pdf', className: 'btn btn-sm btn-outline-secondary' },
      { extend: 'print', className: 'btn btn-sm btn-outline-secondary' },
      { extend: 'colvis', className: 'btn btn-sm btn-outline-secondary' },
    ]
  });
});
</script>
@endsection



