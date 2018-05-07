@extends('layouts.admin')

@section('content')

<div class="container">
    <div class="row">
        <div class="col-md-12">
            @if(Session::has('message'))
            <div class="alert {!! Session::get('type') == 'success' ? 'alert-success' : 'alert-danger' !!}" role="alert">
                {{ Session::get("message") }}
            </div>
            @endif
        </div>
    </div>

    <div class="row mt-5">
        <div class="col-md-6">
            <h1>
                <input type="submit" class="btn btn-success" value="Export" form="export">
                User List ({{ $total }})
            </h1>
        </div>
        <div class="col-md-6">
            @component('admin/components/user-filters', ['contributed' => $contributed,
                                                        'verified' => $verified,
                                                        'filters' => $filters])
            @endcomponent
        </div>
        <div class="col-md-12 mt-3">
            <form id="export" action="{{ route('admin.users.export') . $formGET}}" method="POST">
                @csrf

                <table class="table">
                  <thead class="thead-light">
                    <tr>
                      <th scope="col">
                          <input id="select_all" type="checkbox">
                      </th>
                      <th scope="col">#</th>
                      <th scope="col">Full name</th>
                      <th scope="col">E-mail</th>
                      <th scope="col">Phone</th>
                      <th scope="col">Dates</th>
                      <th scope="col">Actions</th>
                    </tr>
                  </thead>
                  <tbody>
                    @if(count($users) > 0)
                        @foreach($users as $user)
                        <tr>
                            <td>
                                <input type="checkbox" class="checkbox" name="users[]" value="{{ $user->id }}">
                            </td>
                          <th scope="row">
                              {{ $user->id }}
                          </th>
                          <td>{{ $user->full_name() }}</td>
                          <td>
                              {{ $user->email }}
                              <span class="badge badge-{{ ($user->verified) ? 'success' : 'danger' }}">{{ ($user->verified) ? 'verified' : 'non-verified' }}</span>
                              <span class="badge badge-{{ ($user->contributed) ? 'success' : 'danger' }}">{{ ($user->contributed) ? 'contributed' : 'not contributed' }}</span>
                          </td>
                          <td>{{ $user->phone }}</td>
                          <td>
                             <p class="mb-1">
                                 Registered at: <span title='{{ $user->format_date($user->created_at, "precise") }}'>{{ $user->format_date($user->created_at) }}</span>

                             </p>
                             <p class="mb-1">
                                 Verified at: <span title='{{ $user->verified_date("precise") }}'>{{ $user->verified_date() }}</span>

                             </p>
                             <p class="mb-1">
                                 Last online at: <span title='{{ $user->last_online_date("precise") }}'>{{ $user->last_online_date() }}</span>

                             </p>
                          </td>
                          <td>
                             <p><a href="{{ route('admin.users.transaction', $user->id) }}" class="btn btn-success btn-sm">Transactions</a></p>
                             <p><a href="{{ route('admin.users.log', $user->id) }}" class="btn btn-primary btn-sm">IP log</a></p>
                          </td>
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
            </form>

            {{ $users->links() }}

            <script type="text/javascript">

                document.addEventListener('DOMContentLoaded', function() {

                    let form = document.querySelector('#export'),
                        checkboxes = null;

                    form.querySelector('#select_all').addEventListener('change', function() {

                        checkboxes = form.querySelectorAll('.checkbox');

                        if(this.checked) {
                            for(i = 0; i < checkboxes.length; i++) {
                                checkboxes[i].checked = true;
                            }
                        }
                        else {
                            for(i = 0; i < checkboxes.length; i++) {
                                checkboxes[i].checked = false;
                            }
                        }

                    });

                })
            </script>
        </div>
    </div>
</div>
@endsection
