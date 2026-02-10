@extends('layouts.'.config('settings.active_layout'))

@section('content')

<div class="row">
    <div class="col-12">

        <!-- Traffic sources -->
        <div class="card">
            <div class="card-header header-elements-inline">
                <h5 class="card-title">{{ $title }}</h5>
                <div class="header-elements">
                    <div class="form-check form-check-right form-check-switchery form-check-switchery-sm">
                    </div>
                </div>
            </div>

            <div class="card-body">

                <div class="row">

                    <div class="col-sm-12">
                        <form method="GET" action="{{ route('settings.users-mgt.conflicts') }}">
                            <div class="form-group">
                                <label for="role">Select Role</label>
                                <select id="role" name="role" class="form-control">
                                    @foreach ($roles as $role)
                                    <option value="{{ $role->name }}" {{ request('role') == $role->name ? 'selected' : '' }}>{{ $role->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <button type="submit" class="btn btn-primary">Show Conflicts</button>
                        </form>

                        @if (isset($conflicts))
                        @if (count($conflicts) > 0)
                        <table class="table mt-4">
                            <thead>
                                <tr>
                                    <th>Union Council</th>
                                    <th>Conflicting Users</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($conflicts as $conflict)
                                <tr>
                                    <td>{{ $conflict['unionCouncil']->name }}</td>
                                    <td>
                                        <ul>
                                            @foreach ($conflict['users'] as $conflict_user)
                                            <li>{{ $conflict_user->name }} ({{ $conflict_user->email }})</li>
                                            @endforeach
                                        </ul>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                        @else
                        <p class="mt-4">No conflicts found for the selected role.</p>
                        @endif
                        @endif
                    </div>

                </div>

            </div>

        </div>
        <!-- /traffic sources -->

    </div>
</div>

@endsection