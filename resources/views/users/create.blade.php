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
        <label class="form-label">Role</label>
        <select name="roles[]" class="form-select">
            @foreach($roles as $r)
                <option value="{{ $r->name }}">{{ $r->name }}</option>
            @endforeach
        </select>
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



