<form action="{{ route('admin.users.filter') }}" method="GET" class="form-inline">
  <div class="form-group ml-auto mb-2">
    <label for="contributed">Filters: </label>

    <select id="contributed" class="form-control ml-3" name="contributed">
        <option value='none' selected>Choose...</option>
        @foreach($filters['contributed'] as $option)
        <option value="{{ $loop->index }}" {{ ($loop->index == $contributed) ? 'selected' : '' }}>{{ $option }}</option>
        @endforeach
    </select>
  </div>
  <div class="form-group mx-sm-3 mb-2">
      <select id="verified" class="form-control" name="verified">
          <option value='none' selected>Choose...</option>
          @foreach($filters['verified'] as $option)
          <option value="{{ $loop->index }}" {{ ($loop->index == $verified) ? 'selected' : '' }}>{{ $option }}</option>
          @endforeach
      </select>
  </div>
  <button type="submit" class="btn btn-primary mb-2">Filter</button>
</form>
