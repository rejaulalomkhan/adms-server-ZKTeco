@extends('layouts.app')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
    <h4 class="mb-0">Shift Rotations</h4>
    <a href="{{ route('shift-rotations.create') }}" class="btn btn-primary">Create Rotation</a>
    </div>

@if(session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
@endif

<div class="table-responsive">
    <table id="rotations-table" class="table table-striped table-bordered w-100">
        <thead>
            <tr>
                <th>ID</th>
                <th>Employee</th>
                <th>Cycle Weeks</th>
                <th>Effective</th>
                <th>Expiry</th>
                <th>Action</th>
            </tr>
        </thead>
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
$(function() {
  $('#rotations-table').DataTable({
    processing: true,
    serverSide: true,
    responsive: true,
    ajax: '{{ route('shift-rotations.data') }}',
    columns: [
      { data: 'id', name: 'id' },
      { data: 'employee', name: 'employee', orderable: false, searchable: true },
      { data: 'cycle_length_weeks', name: 'cycle_length_weeks' },
      { data: 'effective_date', name: 'effective_date' },
      { data: 'expiry_date', name: 'expiry_date' },
      { data: 'action', orderable: false, searchable: false },
    ],
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


