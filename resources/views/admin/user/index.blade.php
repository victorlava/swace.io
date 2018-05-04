@extends('layouts.admin')

@section('content')

<div class="container">

    <div class="row mt-5">
        <div class="col-md-12">
            <h1>User List</h1>
        </div>
        <div class="col-md-12 mt-3">
            <table class="table">
              <thead class="thead-light">
                <tr>
                  <th scope="col">#</th>
                  <th scope="col">F. name</th>
                  <th scope="col">L. name</th>
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
                      <td>{{ $user->first_name }}</td>
                      <td>{{ $user->last_name }}</td>
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
                      <td>{{ $user->verified_at }}</td>
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
