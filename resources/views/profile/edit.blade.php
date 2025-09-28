@extends('layouts.app')

@section('content')
<h4 class="mb-3">Edit Profile</h4>

@if(session('success'))
  <div class="alert alert-success">{{ session('success') }}</div>
@endif

<form method="POST" action="{{ route('profile.update') }}" class="row g-3">
  @csrf
  <div class="col-md-6">
    <label class="form-label">Name</label>
    <input type="text" name="name" class="form-control" value="{{ old('name', $user->name) }}" required>
  </div>
  <div class="col-md-6">
    <label class="form-label">Email</label>
    <input type="email" name="email" class="form-control" value="{{ old('email', $user->email) }}" required>
  </div>
  <div class="col-md-6">
    <label class="form-label">New Password</label>
    <input type="password" name="password" class="form-control" placeholder="Leave blank to keep current">
  </div>
  <div class="col-md-6">
    <label class="form-label">Confirm Password</label>
    <input type="password" name="password_confirmation" class="form-control" placeholder="Confirm new password">
  </div>
  <div class="col-md-6">
    <label class="form-label">Profile Image</label>
    <input type="file" name="profile_image" accept="image/*" class="form-control" onchange="previewProfileAvatar(event)">
    @if($user->profile_image)
      <div class="mt-2">
        <img id="profileAvatarPreview" src="{{ asset('storage/'.$user->profile_image) }}" alt="Current profile" class="rounded-circle" width="80" height="80">
      </div>
    @endif
  </div>
  <div class="col-12">
    <button type="submit" class="btn btn-primary">Update Profile</button>
  </div>
</form>
<script>
function previewProfileAvatar(e){
  const file = e.target.files && e.target.files[0];
  if(!file) return;
  const url = URL.createObjectURL(file);
  const img = document.getElementById('profileAvatarPreview');
  if(img) img.src = url;
}
</script>
@endsection


