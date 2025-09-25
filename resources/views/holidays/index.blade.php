@extends('layouts.app')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
    <h4 class="mb-0">Holidays</h4>
    <a href="{{ route('holidays.create') }}" class="btn btn-primary">Create Holiday</a>
    </div>

@if(session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
@endif

<div class="table-responsive">
    <table id="holidays-table" class="table table-striped table-bordered w-100">
        <thead>
            <tr>
                <th>ID</th>
                <th>Name</th>
                <th>Date</th>
                <th>Recurring</th>
                <th>Area</th>
                <th>Office</th>
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
  $('#holidays-table').DataTable({
    processing: true,
    serverSide: true,
    responsive: true,
    ajax: '{{ route('holidays.data') }}',
    columns: [
      { data: 'id', name: 'id' },
      { data: 'name', name: 'name' },
      { data: 'date', name: 'date' },
      { data: 'is_recurring', name: 'is_recurring', render: function(val){ return val ? 'Yes' : 'No'; } },
      { data: 'area_name', name: 'area_name', orderable: false },
      { data: 'office_name', name: 'office_name', orderable: false },
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


