<form action="{{ route('admin.users.filter') }}" method="GET" class="form-inline">
  <div class="form-group mb-2">
    <label for="contributed">Filters: </label>

    <select id="contributed" class="form-control ml-3" name="contributed">
        <option value='none' selected>Choose...</option>
        @foreach($filters['contributed'] as $option)
        <option value="{{ $loop->index }}" {{ ($loop->index == $contributed) ? 'selected' : '' }}>{{ $option }}</option>
        @endforeach
    </select>
  </div>
  <div class="form-group mx-sm-2 mb-2">
      <select id="verified" class="form-control" name="verified">
          <option value='none' selected>Choose...</option>
          @foreach($filters['verified'] as $option)
          <option value="{{ $loop->index }}" {{ ($loop->index == $verified) ? 'selected' : '' }}>{{ $option }}</option>
          @endforeach
      </select>
  </div>
  <div class="form-group mx-sm-2 mb-2">
      <input type="number"  class="form-control" name="amount_from" value="" placeholder="0" style="max-width:100px">
  </div>
  <div class="form-group mx-sm-2 mb-2">
      <input type="number"  class="form-control" name="amount_to" value="" placeholder="10000" style="max-width:100px">
  </div>
  <button type="submit" class="btn btn-primary mb-2">Filter</button>
  <a href="{{ route('admin.users.index') }}" class="btn btn-danger mb-2 ml-3">Reset</a>
</form>
