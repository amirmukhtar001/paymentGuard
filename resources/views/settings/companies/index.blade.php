@extends('layouts.' . config('settings.active_layout'))

@section('content')
    <div class="row">
        <div class="col-12">

            <!-- Traffic sources -->
            <div class="card">
                <div class="card-header header-elements-inline">
                    <h6 class="card-title">{{ $title }}</h6>
                    <div class="header-elements">
                        <div class="form-check form-check-right form-check-switchery form-check-switchery-sm">

                            {{-- <label class="form-check-label">
                                Live update:
                                <input type="checkbox" class="form-input-switchery" checked data-fouc>
                            </label> --}}
                        </div>
                    </div>
                </div>

                <div class="card-body">

                    <div class="row">

                        <div class="col-12">

                            <div class="table-responsive" style="min-height: 200px">

                                <table class="table table-striped data_mf_table">

                                    <thead>
                                        <tr>
                                            <th>Action</th>
                                            <th>#</th>
                                            <th>Title</th>
                                            <th>Type</th>
                                            <th>Parent</th>
                                        </tr>
                                    </thead>

                                    <tbody>
                                        @foreach ($items as $item)
                                            <tr>
                                                <td style="width: 180px">

                                                    <a href="{{ route('settings.companies.edit', ['id' => \Illuminate\Support\Facades\Crypt::encrypt($item->id)]) }}"
                                                        class="btn btn-warning btn-icon">
                                                        <i class="tf-icons bx bx-pencil"></i>
                                                    </a>

                                                    <form method="POST"
                                                        action="{{ route('settings.companies.delete', \Illuminate\Support\Facades\Crypt::encrypt($item->id)) }}"
                                                        class="dropdown-item delete" style="display:none; padding: 0px">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="btn btn-danger btn-icon">
                                                            <i class="bx bx-trash tf-icons"></i>
                                                        </button>
                                                    </form>

                                                </td>
                                                <td>{{ $loop->iteration }}</td>
                                                <td><strong>{{ $item->title }}</strong></td>
                                                <td>{{ $item->type->title ?? '' }}</td>
                                                <td>{{ $item->parent->title ?? '-' }}</td>

                                            </tr>
                                        @endforeach
                                    </tbody>

                                </table>

                            </div>

                        </div>

                    </div>

                </div>

            </div>
            <!-- /traffic sources -->

        </div>
    </div>
@endsection
