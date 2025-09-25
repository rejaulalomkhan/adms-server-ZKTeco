@extends('layouts.app')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
    <h4 class="mb-0">Shifts</h4>
    <a href="{{ route('shifts.create') }}" class="btn btn-primary">Create Shift</a>
    </div>

@if(session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
@endif

<div class="table-responsive">
    <table id="shifts-table" class="table table-striped table-bordered w-100">
        <thead>
            <tr>
                <th>ID</th>
                <th>Name</th>
                <th>Code</th>
                <th>Start</th>
                <th>End</th>
                <th>Overnight</th>
                <th>Break (min)</th>
                <th>Grace (min)</th>
                <th>Expected Hours</th>
                <th>Active</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            @if(isset($shifts) && count($shifts))
                @foreach($shifts as $shift)
                <tr>
                    <td>{{ $shift->id }}</td>
                    <td>{{ $shift->name }}</td>
                    <td>{{ $shift->code }}</td>
                    <td>{{ $shift->start_time }}</td>
                    <td>{{ $shift->end_time }}</td>
                    <td>{{ $shift->is_overnight ? 'Yes' : 'No' }}</td>
                    <td>{{ $shift->break_minutes }}</td>
                    <td>{{ $shift->grace_minutes }}</td>
                    <td>{{ $shift->expected_hours }}</td>
                    <td>{{ $shift->active ? 'Yes' : 'No' }}</td>
                    <td>
                        <a href="{{ route('shifts.edit', $shift->id) }}" class="btn btn-sm btn-outline-primary">Edit</a>
                    </td>
                </tr>
                @endforeach
            @endif
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
$(function() {
  $('#shifts-table').DataTable({
    processing: true,
    serverSide: false,
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


