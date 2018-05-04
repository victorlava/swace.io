@extends('layouts.admin')

@section('content')

<div class="container">

    <div class="row mt-5">
        <h1 class="float-left">User (#{{ $user->id }}) ip address log</h1>
        <div class="col-md-7 float-right">
            <div class="row">
                <div class="col-md-12">
                    <a href="{{ URL::previous() }}" class="float-right btn btn-danger btn-lg">Back</a>
                </div>
            </div>
        </div>
        <div class="col-md-12 mt-3">
            <table class="table">
              <thead class="thead-light">
                <tr>
                  <th scope="col">#</th>
                  <th scope="col">IP Address</th>
                  <th scope="col">Log in</th>
                  <th scope="col">Log out</th>
                  <th scope="col">Online time</th>
                </tr>
              </thead>
              <tbody>
                @if(count($user->logs) > 0)
                    @foreach($user->logs as $log)
                    <tr>
                      <th scope="row">
                          {{ $log->id }}
                      </th>
                      <td>{{ $log->ip_address }}</td>
                      <td>{{ $log->log_in }}</td>
                      <td>{{ $log->log_out }}</td>
                      <td>{{ $log->online_time() }}</td>
                    </tr>
                    @endforeach
                @else:
                <tr>
                    <td colspan="11">
                        User ip address log is empty.
                    </td>
                </tr>
                @endif
              </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
