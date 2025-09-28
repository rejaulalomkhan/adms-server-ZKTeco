@extends('layouts.app')

@section('content')
<h4 class="mb-3">Edit Employee</h4>

<form method="POST" action="{{ route('users.update', $user->id) }}" class="row g-3" enctype="multipart/form-data">
    @csrf
    @method('PUT')
    <div class="col-md-6">
        <label class="form-label">Name</label>
        <input type="text" name="name" class="form-control" value="{{ old('name', $user->name) }}" required>
    </div>
    <div class="col-md-6">
        <label class="form-label">Email</label>
        <input type="email" name="email" class="form-control" value="{{ old('email', $user->email) }}" required>
    </div>
    <div class="col-md-6">
        <label class="form-label">Password</label>
        <input type="password" name="password" class="form-control" placeholder="Leave blank to keep current">
    </div>
    <div class="col-md-6">
        <label class="form-label">Profile Image</label>
        <input type="file" name="profile_image" accept="image/*" class="form-control" onchange="previewEditAvatar(event)">
        @if($user->profile_image)
        <div class="mt-2">
            <img id="editAvatarPreview" src="{{ asset('storage/'.$user->profile_image) }}" alt="Current profile" class="rounded-circle" width="80" height="80">
        </div>
        @endif
    </div>
    <div class="col-md-6">
        <label class="form-label">Office</label>
        <select name="office_id" class="form-select">
            <option value="">-- None --</option>
            @foreach($offices as $o)
                <option value="{{ $o->id }}" {{ (old('office_id', $user->office_id)==$o->id)?'selected':'' }}>{{ $o->name }}</option>
            @endforeach
        </select>
    </div>

    <div class="col-md-6">
        <label class="form-label">Designation</label>
        <input type="text" name="designation" class="form-control" value="{{ old('designation', $user->designation) }}">
    </div>
    <div class="col-md-6">
        <label class="form-label">Department</label>
        <input type="text" name="department" class="form-control" value="{{ old('department', $user->department) }}">
    </div>
    <div class="col-md-6">
        <label class="form-label">Join Date</label>
        <input type="date" name="join_date" class="form-control" value="{{ old('join_date', optional($user->join_date)->format('Y-m-d')) }}">
    </div>
    <div class="col-md-6">
        <label class="form-label">Fingerprint ID</label>
        <input type="text" name="fingerprint_id" class="form-control" value="{{ old('fingerprint_id', $user->fingerprint_id) }}">
    </div>
    <div class="col-md-6">
        <label class="form-label">Assign Shift</label>
        <select name="shift_id" class="form-select">
            <option value="">-- None --</option>
            @foreach(\App\Models\Shift::orderBy('name')->get(['id','name']) as $s)
                <option value="{{ $s->id }}" {{ old('shift_id')===$s->id ? 'selected' : '' }}>{{ $s->name }}</option>
            @endforeach
        </select>
    </div>

    <div class="col-md-6">
        <label class="form-label">Role</label>
        <select name="roles[]" class="form-select">
            @foreach($roles as $r)
                <option value="{{ $r->name }}" {{ in_array($r->name, old('roles', $userRoleNames)) ? 'selected' : '' }}>{{ $r->name }}</option>
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
            </div>
            <div class="col-md-6">
                <label class="form-label">Document Types (comma separated)</label>
                <input type="text" name="documents_types[]" class="form-control" placeholder="e.g. nominee_photo, certificate, official_doc">
            </div>
        </div>
    </div>

    <div class="col-12">
        <button type="submit" class="btn btn-primary">Update</button>
        <a href="{{ route('users.index') }}" class="btn btn-secondary">Cancel</a>
    </div>
</form>
<script>
function previewEditAvatar(e){
  const file = e.target.files && e.target.files[0];
  if(!file) return;
  const url = URL.createObjectURL(file);
  const img = document.getElementById('editAvatarPreview');
  if(img) img.src = url;
}
</script>
@endsection


