@extends('layouts.app')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
    <h4 class="mb-0">Dashboard</h4>
    <div class="d-flex align-items-center gap-2">
        <input type="date" id="date" class="form-control form-control-sm" />
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

<div class="row mt-4">
  <div class="col-12 col-lg-4">
    <div class="card h-100">
      <div class="card-header d-flex justify-content-between align-items-center">
        <span>Recent Attendance</span>
        <div>
          <select id="recent-limit" class="form-select form-select-sm" style="width:auto;display:inline-block">
            <option value="10">10</option>
            <option value="20" selected>20</option>
            <option value="50">50</option>
          </select>
        </div>
      </div>
      <div class="card-body p-0">
        <div class="table-responsive">
          <table class="table table-sm mb-0">
            <thead>
              <tr>
                <th>Time</th>
                <th>Emp</th>
                <th>SN</th>
              </tr>
            </thead>
            <tbody id="recent-body">
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>
  <div class="col-12 col-lg-8">
    <!-- reserved for future charts -->
  </div>
  </div>

<script>
async function loadSummary() {
  const range = document.getElementById('range').value;
  const dateVal = document.getElementById('date').value;
  const params = new URLSearchParams();
  params.set('range', range);
  if (dateVal) params.set('date', dateVal);
  const res = await fetch(`/api/dashboard/summary?${params.toString()}`);
  const data = await res.json();
  document.getElementById('kpi-total').textContent = data.totalEmployees ?? '-';
  document.getElementById('kpi-present').textContent = data.present ?? '-';
  document.getElementById('kpi-late').textContent = data.late ?? '-';
  document.getElementById('kpi-absent').textContent = data.absent ?? '-';
}
async function loadRecent() {
  const limit = document.getElementById('recent-limit').value;
  const res = await fetch(`/api/dashboard/recent-attendance?limit=${limit}`);
  const rows = await res.json();
  const body = document.getElementById('recent-body');
  body.innerHTML = '';
  rows.forEach(r => {
    const tr = document.createElement('tr');
    tr.innerHTML = `<td>${r.timestamp}</td><td>${r.employee_name ?? r.employee_id}</td><td>${r.sn}</td>`;
    body.appendChild(tr);
  });
}
document.getElementById('refresh').addEventListener('click', loadSummary);
document.getElementById('range').addEventListener('change', loadSummary);
document.getElementById('date').addEventListener('change', loadSummary);
document.getElementById('recent-limit').addEventListener('change', loadRecent);
loadSummary();
loadRecent();
setInterval(loadRecent, 15000);
</script>
@endsection


