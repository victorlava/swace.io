@extends('layouts.admin')

@section('content')

<div class="container">

    <div class="row mt-5">
        <div class="col-md-6">
            <h1>User List</h1>
        </div>
        <div class="col-md-6">
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
        </div>
        <div class="col-md-12 mt-3">
            <table class="table">
              <thead class="thead-light">
                <tr>
                  <th scope="col">#</th>
                  <th scope="col">Full name</th>
                  <th scope="col">Email</th>
                  <th scope="col">Phone</th>
                  <th scope="col">IP log</th>
                  <th scope="col">History</th>
                  <th scope="col">Reg. date</th>
                  <th scope="col">Email confirm date</th>
                  <th scope="col">Last Online date</th>
                </tr>
              </thead>
              <tbody>
                @if(count($users) > 0)
                    @foreach($users as $user)
                    <tr>
                      <th scope="row">
                          {{ $user->id }}
                      </th>
                      <td>{{ $user->full_name() }}</td>
                      <td>
                          {{ $user->email }}
                           <span class="badge badge-{{ ($user->verified) ? 'success' : 'danger' }}">{{ ($user->verified) ? 'verified' : 'non-verified' }}</span>
                      </td>
                      <td>{{ $user->phone }}</td>
                      <td>
                          <a href="{{ route('admin.users.log', $user->id) }}">Log</a>
                      </td>
                      <td>
                          <a href="{{ route('admin.users.transaction', $user->id) }}">Transaction history</a>
                      </td>
                      <td>{{ $user->created_at }}</td>
                      <td>{{ $user->verified_date() }}</td>
                      <td>{{ $user->last_online_date() }}</td>
                    </tr>
                    @endforeach
                @else:
                <tr>
                    <td colspan="11">
                        No users yet.
                    </td>
                </tr>
                @endif
              </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
