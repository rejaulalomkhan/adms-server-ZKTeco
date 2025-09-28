@extends('layouts.app')

@section('content')
<h4 class="mb-3">Add Employee</h4>

<form method="POST" action="{{ route('users.store') }}" class="row g-3" enctype="multipart/form-data">
    @csrf
    <div class="col-md-6">
        <label class="form-label">Name</label>
        <input type="text" name="name" class="form-control" required>
    </div>
    <div class="col-md-6">
        <label class="form-label">Email</label>
        <input type="email" name="email" class="form-control" required>
    </div>
    <div class="col-md-6">
        <label class="form-label">Password</label>
        <input type="password" name="password" class="form-control" required>
    </div>
    <div class="col-md-6">
        <label class="form-label">Profile Image</label>
        <input type="file" name="profile_image" accept="image/*" class="form-control" onchange="previewCreateAvatar(event)">
        <div class="mt-2">
            <img id="createAvatarPreview" src="https://ui-avatars.com/api/?background=0D8ABC&color=fff&name=" class="rounded-circle" width="80" height="80" alt="preview">
        </div>
        <small class="text-muted">Max size 2MB</small>
    </div>
    <div class="col-md-6">
        <label class="form-label">Office</label>
        <select name="office_id" class="form-select">
            <option value="">-- None --</option>
            @foreach($offices as $o)
                <option value="{{ $o->id }}">{{ $o->name }}</option>
            @endforeach
        </select>
    </div>
    <div class="col-md-6">
        <label class="form-label">Designation</label>
        <input type="text" name="designation" class="form-control">
    </div>
    <div class="col-md-6">
        <label class="form-label">Department</label>
        <input type="text" name="department" class="form-control">
    </div>
    <div class="col-md-6">
        <label class="form-label">Join Date</label>
        <input type="date" name="join_date" class="form-control">
    </div>
    <div class="col-md-6">
        <label class="form-label">Fingerprint ID</label>
        <input type="text" name="fingerprint_id" class="form-control" placeholder="Enroll ID from device">
    </div>
    <div class="col-md-6">
        <label class="form-label">Assign Shift</label>
        <select name="shift_id" class="form-select">
            <option value="">-- None --</option>
            @foreach(\App\Models\Shift::orderBy('name')->get(['id','name']) as $s)
                <option value="{{ $s->id }}">{{ $s->name }}</option>
            @endforeach
        </select>
    </div>
    <div class="col-md-6">
        <label class="form-label">Role</label>
        <select name="roles[]" class="form-select">
            @foreach($roles as $r)
                <option value="{{ $r->name }}">{{ $r->name }}</option>
            @endforeach
        </select>
    </div>
    <div class="col-12">
        <hr>
        <h6>Documents</h6>
        <div class="row g-2">
            <div class="col-md-6">
                <label class="form-label">Upload Files</label>
                <input type="file" name="documents[]" class="form-control" multiple>
                <small class="text-muted">You can select multiple files</small>
            </div>
            <div class="col-md-6">
                <label class="form-label">Document Types (comma separated)</label>
                <input type="text" name="documents_types[]" class="form-control" placeholder="e.g. nominee_photo, certificate, official_doc">
            </div>
        </div>
    </div>
    <div class="col-12">
        <button type="submit" class="btn btn-primary">Save</button>
        <a href="{{ route('users.index') }}" class="btn btn-secondary">Cancel</a>
    </div>
</form>
<script>
function previewCreateAvatar(e){
  const file = e.target.files && e.target.files[0];
  if(!file) return;
  const url = URL.createObjectURL(file);
  const img = document.getElementById('createAvatarPreview');
  img.src = url;
}
</script>
@endsection



