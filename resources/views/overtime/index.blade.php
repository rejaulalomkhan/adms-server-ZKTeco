@extends('layouts.app')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
    <h4 class="mb-0">Overtime</h4>
    <form method="POST" action="{{ route('overtime.calculate') }}" class="d-flex align-items-center gap-2">
        @csrf
        <input type="date" name="start_date" class="form-control form-control-sm" required>
        <input type="date" name="end_date" class="form-control form-control-sm" required>
        <button type="submit" class="btn btn-sm btn-outline-primary">Calculate</button>
    </form>
    </div>

@if(session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
@endif

<div class="table-responsive">
    <table id="overtime-table" class="table table-striped table-bordered w-100">
        <thead>
            <tr>
                <th>ID</th>
                <th>Employee</th>
                <th>Date</th>
                <th>Minutes</th>
                <th>Type</th>
                <th>Approved By</th>
                <th>Approved At</th>
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
  const table = $('#overtime-table').DataTable({
    processing: true,
    serverSide: true,
    responsive: true,
    ajax: {
      url: '{{ route('overtime.data') }}',
      data: function(d){
        d.start_date = $('input[name="start_date"]').val();
        d.end_date = $('input[name="end_date"]').val();
      }
    },
    columns: [
      { data: 'id', name: 'id' },
      { data: 'employee', name: 'employee', orderable: false },
      { data: 'date', name: 'date' },
      { data: 'minutes', name: 'minutes' },
      { data: 'type', name: 'type' },
      { data: 'approved_by_name', name: 'approved_by_name', orderable: false },
      { data: 'approved_at', name: 'approved_at' },
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

  $('form').on('submit', function(){
    setTimeout(function(){ table.ajax.reload(); }, 200);
  });
});
</script>
@endsection


