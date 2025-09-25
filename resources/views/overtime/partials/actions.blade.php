<form action="{{ $approveUrl }}" method="POST" onsubmit="return confirm('Approve this overtime entry?');">
  @csrf
  <button type="submit" class="btn btn-sm btn-outline-success">Approve</button>
</form>


