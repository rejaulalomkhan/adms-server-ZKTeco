@extends('layouts.app')

@section('content')
<h4 class="mb-3">Create Shift Rotation</h4>

<form method="POST" action="{{ route('shift-rotations.store') }}" class="row g-3">
    @csrf
    <div class="col-md-6">
        <label class="form-label">Employee (optional)</label>
        <select name="employee_id" class="form-select">
            <option value="">-- Any/Group default --</option>
            @foreach($users as $u)
                <option value="{{ $u->id }}">{{ $u->name }}</option>
            @endforeach
        </select>
    </div>
    <div class="col-md-6">
        <label class="form-label">Cycle Length (weeks)</label>
        <input type="number" name="cycle_length_weeks" class="form-control" min="1" max="8" value="2" required>
    </div>
    <div class="col-md-6">
        <label class="form-label">Effective Date</label>
        <input type="date" name="effective_date" class="form-control">
    </div>
    <div class="col-md-6">
        <label class="form-label">Expiry Date</label>
        <input type="date" name="expiry_date" class="form-control">
    </div>

    <div class="col-12">
        <div class="card">
            <div class="card-header">Week to Shift Mapping</div>
            <div class="card-body">
                <div id="weeks-container">
                    <div class="row g-2 align-items-end week-row">
                        <div class="col-md-3">
                            <label class="form-label">Week Index</label>
                            <input type="number" name="weeks[0][week_index]" class="form-control" min="1" value="1" required>
                        </div>
                        <div class="col-md-7">
                            <label class="form-label">Shift</label>
                            <select name="weeks[0][shift_id]" class="form-select" required>
                                @foreach($shifts as $s)
                                    <option value="{{ $s->id }}">{{ $s->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-2">
                            <button type="button" class="btn btn-outline-secondary w-100 add-week">Add</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-12">
        <button type="submit" class="btn btn-primary">Save</button>
        <a href="{{ route('shift-rotations.index') }}" class="btn btn-secondary">Cancel</a>
    </div>
</form>

<script>
document.addEventListener('click', function(e){
  if (e.target && e.target.classList.contains('add-week')) {
    const container = document.getElementById('weeks-container');
    const index = container.querySelectorAll('.week-row').length;
    const row = document.createElement('div');
    row.className = 'row g-2 align-items-end week-row mt-2';
    row.innerHTML = `
      <div class="col-md-3">
        <label class="form-label">Week Index</label>
        <input type="number" name="weeks[${index}][week_index]" class="form-control" min="1" value="${index+1}" required>
      </div>
      <div class="col-md-7">
        <label class="form-label">Shift</label>
        <select name="weeks[${index}][shift_id]" class="form-select" required>
          ${`@foreach($shifts as $s)<option value="{{ $s->id }}">{{ $s->name }}</option>@endforeach`}
        </select>
      </div>
      <div class="col-md-2">
        <button type="button" class="btn btn-outline-danger w-100 remove-week">Remove</button>
      </div>`;
    container.appendChild(row);
  }
  if (e.target && e.target.classList.contains('remove-week')) {
    e.target.closest('.week-row').remove();
  }
});
</script>
@endsection


