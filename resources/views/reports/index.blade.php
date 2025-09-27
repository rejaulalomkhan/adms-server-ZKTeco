@extends('layouts.app')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
  <h4 class="mb-0">Reports</h4>
  <div class="d-flex gap-2 align-items-center">
    <select id="quickRange" class="form-select form-select-sm" style="width:auto">
      <option value="today" selected>Today</option>
      <option value="yesterday">Yesterday</option>
      <option value="this_week">This Week</option>
      <option value="this_month">This Month</option>
    </select>
    <input type="date" id="globalStart" class="form-control form-control-sm" style="width:auto">
    <input type="date" id="globalEnd" class="form-control form-control-sm" style="width:auto">
    <button id="applyRange" type="button" class="btn btn-sm btn-outline-primary">Apply</button>
    <span id="reportsLoader" class="ms-2 d-none">
      <span class="spinner-border spinner-border-sm text-primary" role="status" aria-hidden="true"></span>
    </span>
  </div>
  </div>

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
    <div class="mb-2 text-muted small">Attendance by day (First In / Last Out). Use the global range above.</div>
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
    <div class="mb-2 text-muted small">Late arrivals beyond policy. Use the global range above.</div>
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
    <div class="mb-2 text-muted small">Absences for a single day. Uses the global start date.</div>
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
  // helpers for range
  function setRange(range){
    const now = new Date();
    const pad = (n)=> String(n).padStart(2,'0');
    function toYMD(d){ return `${d.getFullYear()}-${pad(d.getMonth()+1)}-${pad(d.getDate())}`; }
    let s,e;
    if(range==='yesterday'){ const y=new Date(now); y.setDate(y.getDate()-1); s=toYMD(y); e=toYMD(y); }
    else if(range==='this_week'){ const d=new Date(now); const day=d.getDay()||7; const start=new Date(d); start.setDate(d.getDate()-day+1); const end=new Date(start); end.setDate(start.getDate()+6); s=toYMD(start); e=toYMD(end); }
    else if(range==='this_month'){ const start=new Date(now.getFullYear(), now.getMonth(), 1); const end=new Date(now.getFullYear(), now.getMonth()+1, 0); s=toYMD(start); e=toYMD(end); }
    else { s=toYMD(now); e=toYMD(now); }
    document.getElementById('globalStart').value=s; document.getElementById('globalEnd').value=e;
  }
  setRange('this_month');
  document.getElementById('quickRange').addEventListener('change', function(){ setRange(this.value); });

  function getStart(){ return document.getElementById('globalStart').value; }
  function getEnd(){ return document.getElementById('globalEnd').value; }

  const attendanceTable = initTable('#attendance-table', '{{ route('reports.attendance') }}', () => ({
    start_date: getStart(),
    end_date: getEnd(),
  }), [
    { data: 'employee_name', name: 'employee_name' },
    { data: 'work_date', name: 'work_date' },
    { data: 'first_in', name: 'first_in' },
    { data: 'last_out', name: 'last_out' },
    { data: 'punches', name: 'punches' },
  ]);

  const latenessTable = initTable('#lateness-table', '{{ route('reports.lateness') }}', () => ({
    start_date: getStart(),
    end_date: getEnd(),
  }), [
    { data: 'employee_name', name: 'employee_name' },
    { data: 'work_date', name: 'work_date' },
    { data: 'first_in', name: 'first_in' },
    { data: 'late_minutes', name: 'late_minutes' },
  ]);

  const absenceTable = initTable('#absence-table', '{{ route('reports.absence') }}', () => ({
    date: getStart(),
  }), [
    { data: 'employee_name', name: 'employee_name' },
  ]);

  const loader = document.getElementById('reportsLoader');
  function showLoader(){ loader && loader.classList.remove('d-none'); }
  function hideLoader(){ loader && loader.classList.add('d-none'); }

  // hide loader when each table finishes
  $('#attendance-table').on('xhr.dt', function(){ hideLoader(); });
  $('#lateness-table').on('xhr.dt', function(){ hideLoader(); });
  $('#absence-table').on('xhr.dt', function(){ hideLoader(); });

  document.getElementById('applyRange').addEventListener('click', function(e){
    e.preventDefault();
    showLoader();
    attendanceTable.ajax.reload();
    latenessTable.ajax.reload();
    absenceTable.ajax.reload();
  });
  // initial load
  showLoader();
  attendanceTable.ajax.reload();
  latenessTable.ajax.reload();
  absenceTable.ajax.reload();
});
</script>
@endsection


