<div class="btn-group btn-group-sm" role="group">
  <a href="{{ $editUrl }}" class="btn btn-outline-primary">Edit</a>
  <form action="{{ $deleteUrl }}" method="POST" onsubmit="return confirm('Delete this office?');">
    @csrf
    @method('DELETE')
    <button type="submit" class="btn btn-outline-danger">Delete</button>
  </form>
  </div>


