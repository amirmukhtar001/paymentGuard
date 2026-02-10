@extends('layouts.'.config('settings.active_layout'))

@push('stylesheets')
    @include('components.datatables.cdn-styles')
@endpush

@push('scripts')
    @include('components.datatables.cdn-scripts')
<script>
    $(document).ready(function() {

        $('#recordTable').DataTable({
            dom: 'Bfrtip',
            buttons: [
                'copy', 'csv', 'excel', 'pdf', 'print'
            ],
            pageLength: 25,
        });
    });
</script>
@endpush

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
                        <form method="GET" action="{{ route('settings.users-mgt.check-un-assigned-uc') }}">
                            <div class="form-group">
                                <label for="role_slug_filter" class="control-label req">Designation/Role</label>
                                <select name="role_slug_filter" class="form-control" id="role_slug_filter">
                                    @foreach($roles as $slug => $name)
                                        <option value="{{ $slug }}" {{ (request()->role_slug_filter ?? 'agriculture.field.assistant') == $slug ? 'selected' : '' }}>{{ $name }}</option>
                                    @endforeach
                                </select>

                            </div>

                            <div class="form-group">
                                <label for="district_id">District</label>
                                <select name="district_id" id="role" class="form-control">
                                    <option value="">Select District</option>
                                    @foreach($districts as $id => $name)
                                        <option value="{{ $id }}" {{ request('district_id') == $id ? 'selected' : '' }}>{{ $name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <button type="submit" class="btn btn-primary">Show</button>
                        </form>

                    </div>

                    <div class="col-sm-12 mt-3">
                        <table class="table table-bordered  mt-4" id="recordTable">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Name</th>

                                    <th>Farmer Count</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($unusedUnionCouncils as $unionCouncil)
                                <tr>
                                    <td>{{ $unionCouncil->id }}</td>
                                    <td>{{ $unionCouncil->name }}</td>
                                    <td>{{ $unionCouncil->farmers_count }}</td>
                                </tr>
                                @empty
                                <tr>
                                    <td></td>
                                    <td>No unused union councils found.</td>
                                    <td></td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                </div>

            </div>

        </div>
        <!-- /traffic sources -->

    </div>
</div>

@endsection
