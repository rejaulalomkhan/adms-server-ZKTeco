@extends('layouts.app')

@section('content')
<h4 class="mb-3">Reports</h4>

<ul class="nav nav-tabs" id="reportTabs" role="tablist">
  <li class="nav-item" role="presentation">
    <button class="nav-link active" id="attendance-tab" data-bs-toggle="tab" data-bs-target="#attendance" type="button" role="tab">Attendance</button>
  </li>
  <li class="nav-item" role="presentation">
    <button class="nav-link" id="lateness-tab" data-bs-toggle="tab" data-bs-target="#lateness" type="button" role="tab">Lateness</button>
  </li>
  <li class="nav-item" role="presentation">
    <button class="nav-link" id="absence-tab" data-bs-toggle="tab" data-bs-target="#absence" type="button" role="tab">Absence</button>
  </li>
</ul>

<div class="tab-content mt-3">
  <div class="tab-pane fade show active" id="attendance" role="tabpanel">
    <form id="attendanceForm" class="d-flex gap-2 align-items-center mb-2">
      <input type="date" name="start_date" class="form-control form-control-sm" required>
      <input type="date" name="end_date" class="form-control form-control-sm" required>
      <button class="btn btn-sm btn-outline-primary" type="submit">Load</button>
    </form>
    <div class="table-responsive">
      <table id="attendance-table" class="table table-striped table-bordered w-100">
        <thead>
          <tr>
            <th>Employee</th>
            <th>Date</th>
            <th>First In</th>
            <th>Last Out</th>
            <th>Punches</th>
          </tr>
        </thead>
      </table>
    </div>
  </div>

  <div class="tab-pane fade" id="lateness" role="tabpanel">
    <form id="latenessForm" class="d-flex gap-2 align-items-center mb-2">
      <input type="date" name="start_date" class="form-control form-control-sm" required>
      <input type="date" name="end_date" class="form-control form-control-sm" required>
      <button class="btn btn-sm btn-outline-primary" type="submit">Load</button>
    </form>
    <div class="table-responsive">
      <table id="lateness-table" class="table table-striped table-bordered w-100">
        <thead>
          <tr>
            <th>Employee</th>
            <th>Date</th>
            <th>First In</th>
            <th>Late Minutes</th>
          </tr>
        </thead>
      </table>
    </div>
  </div>

  <div class="tab-pane fade" id="absence" role="tabpanel">
    <form id="absenceForm" class="d-flex gap-2 align-items-center mb-2">
      <input type="date" name="date" class="form-control form-control-sm" required>
      <button class="btn btn-sm btn-outline-primary" type="submit">Load</button>
    </form>
    <div class="table-responsive">
      <table id="absence-table" class="table table-striped table-bordered w-100">
        <thead>
          <tr>
            <th>Employee</th>
          </tr>
        </thead>
      </table>
    </div>
  </div>
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
function initTable(selector, url, getParams, columns) {
  return $(selector).DataTable({
    processing: true,
    serverSide: true,
    responsive: true,
    ajax: {
      url: url,
      data: function(d){ Object.assign(d, getParams()); }
    },
    columns: columns,
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
}

$(function(){
  const attendanceTable = initTable('#attendance-table', '{{ route('reports.attendance') }}', () => ({
    start_date: document.querySelector('#attendanceForm [name="start_date"]').value,
    end_date: document.querySelector('#attendanceForm [name="end_date"]').value,
  }), [
    { data: 'employee_name', name: 'employee_name' },
    { data: 'work_date', name: 'work_date' },
    { data: 'first_in', name: 'first_in' },
    { data: 'last_out', name: 'last_out' },
    { data: 'punches', name: 'punches' },
  ]);
  document.getElementById('attendanceForm').addEventListener('submit', function(e){ e.preventDefault(); attendanceTable.ajax.reload(); });

  const latenessTable = initTable('#lateness-table', '{{ route('reports.lateness') }}', () => ({
    start_date: document.querySelector('#latenessForm [name="start_date"]').value,
    end_date: document.querySelector('#latenessForm [name="end_date"]').value,
  }), [
    { data: 'employee_name', name: 'employee_name' },
    { data: 'work_date', name: 'work_date' },
    { data: 'first_in', name: 'first_in' },
    { data: 'late_minutes', name: 'late_minutes' },
  ]);
  document.getElementById('latenessForm').addEventListener('submit', function(e){ e.preventDefault(); latenessTable.ajax.reload(); });

  const absenceTable = initTable('#absence-table', '{{ route('reports.absence') }}', () => ({
    date: document.querySelector('#absenceForm [name="date"]').value,
  }), [
    { data: 'employee_name', name: 'employee_name' },
  ]);
  document.getElementById('absenceForm').addEventListener('submit', function(e){ e.preventDefault(); absenceTable.ajax.reload(); });
});
</script>
@endsection


