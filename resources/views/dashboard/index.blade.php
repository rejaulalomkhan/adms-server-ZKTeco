@extends('layouts.app')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
    <h4 class="mb-0">Dashboard</h4>
    <div class="d-flex align-items-center gap-2">
        <select id="range" class="form-select form-select-sm" style="width:auto">
            <option value="today" selected>Today</option>
            <option value="yesterday">Yesterday</option>
            <option value="this_week">This Week</option>
            <option value="this_month">This Month</option>
        </select>
        <button id="refresh" class="btn btn-sm btn-outline-primary">Refresh</button>
    </div>
</div>

<div class="row g-3" id="kpis">
    <div class="col-6 col-md-3">
        <div class="card text-center">
            <div class="card-body">
                <div class="text-muted">Total Employees</div>
                <div id="kpi-total" class="fs-3 fw-bold">-</div>
            </div>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="card text-center">
            <div class="card-body">
                <div class="text-muted">Present</div>
                <div id="kpi-present" class="fs-3 fw-bold">-</div>
            </div>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="card text-center">
            <div class="card-body">
                <div class="text-muted">Late</div>
                <div id="kpi-late" class="fs-3 fw-bold">-</div>
            </div>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="card text-center">
            <div class="card-body">
                <div class="text-muted">Absent</div>
                <div id="kpi-absent" class="fs-3 fw-bold">-</div>
            </div>
        </div>
    </div>
</div>

<script>
async function loadSummary() {
  const range = document.getElementById('range').value;
  const res = await fetch(`/api/dashboard/summary?range=${range}`);
  const data = await res.json();
  document.getElementById('kpi-total').textContent = data.totalEmployees ?? '-';
  document.getElementById('kpi-present').textContent = data.present ?? '-';
  document.getElementById('kpi-late').textContent = data.late ?? '-';
  document.getElementById('kpi-absent').textContent = data.absent ?? '-';
}
document.getElementById('refresh').addEventListener('click', loadSummary);
document.getElementById('range').addEventListener('change', loadSummary);
loadSummary();
</script>
@endsection


