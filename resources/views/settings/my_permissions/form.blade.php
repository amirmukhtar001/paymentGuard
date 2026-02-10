@extends('layouts.'.config('settings.active_layout'))

@push('scripts')

    <script type="text/javascript">
        $(document).ready(function () {
            $("#app_id").change(function () {
                var app_id = $(this).val()
                $.ajax({
                    type: 'post',
                    url: '{{ route('settings.menus.menus-by-app-id') }}',
                    data: {
                        app_id: app_id,
                        _token: '{{ csrf_token() }}'
                    },
                    success: function (res) {
                        var options = "<option value>Select a Menu</option>"
                        $.each(res, function (i, k) {
                            options += "<option value='" + i + "'>" + k + "</option>"
                        })
                        $("#menu_id").html(options)
                    }
                })
            })

            $(".new_route").click(function (e) {
                e.preventDefault()
                var new_route_html = $(".rgr_cont").children('.row').html()

                @if($item->exists)
                var new_route_html_rem = "<div class='row new_route_form'>" +
                    "" + new_route_html + "" +
                    "</div>";
                @else
                var new_route_html_rem = "<div class='row new_route_form'>" +
                    "" + new_route_html + "" +
                    "<div class='col-1'><a class='btn btn-sm btn-danger remove_route'><i class='bx bx-trash text-white'></i></a></div>" +
                    "</div>";
                @endif

                $(".rgr_cont").after(new_route_html_rem)
            })

            $(".rgr_configs").on('click', '.remove_route', function () {
                $(this).closest('.new_route_form').remove()
            })

            $(document).on("change", ".is_default_dd", function () {
                $('.is_default_dd').not(this).children('option:first-child').prop('selected', true);
            })
        })
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

                            {{--<label class="form-check-label">
                                Live update:
                                <input type="checkbox" class="form-input-switchery" checked data-fouc>
                            </label>--}}
                        </div>
                    </div>
                </div>

                <div class="card-body">

                    <div class="row">

                        <div class="col-12">

                            @php
                                $actionUrl = $item->exists
                                    ? route('settings.my-permissions.update', \Illuminate\Support\Facades\Crypt::encrypt($item->id))
                                    : route('settings.my-permissions.store');
                            @endphp

                            <form enctype="multipart/form-data" method="POST" action="{{ $actionUrl }}">
                                @csrf
                                @if($item->exists)
                                    @method('PUT')
                                @endif

                            <div class="row">

                                <div class="col-6">
                                    <div class="form-group">
                                        <label for="name" class="form-label req">Permission Name</label>
                                        <span
                                                class="help">@if(session()->has('errors')) {!! session()->get('errors')->first('name') !!}@endif</span>
                                        <input type="text" name="name" id="name" class="form-control" required value="{{ old('name', $item->name) }}">
                                    </div>
                                </div>

                                <div class="col-6">
                                    <div class="row">
                                        <div class="col-6">
                                            <div class="form-group">
                                                <label for="slug" class="form-label req">Permission Slug</label>
                                                <span
                                                        class="help">@if(session()->has('errors')) {!! session()->get('errors')->first('slug') !!}@endif</span>
                                                <input type="text" name="slug" id="slug" class="form-control" required value="{{ old('slug', $item->slug) }}">
                                            </div>
                                        </div>
                                        <div class="col-6">
                                            <div class="form-group">
                                                <label for="model" class="control-label">Select Model</label>

                                                <span
                                                        class="help">@if(Session::has('errors')) {!! Session::get('errors')->first('model') !!} @endif</span>
                                                <select name="model" id="model" class="form-select select2">
                                                    @foreach(['Permission' => 'Permission Model'] as $val => $lbl)
                                                        <option value="{{ $val }}" @if(old('model', $item->model) == $val) selected @endif>{{ $lbl }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-12">
                                    <div class="form-group">
                                        <label for="menu_id" class="control-label">Select a Menu</label>

                                        <span
                                                class="help">@if(Session::has('errors')) {!! Session::get('errors')->first('menu_id') !!} @endif</span>
                                        <select name="menu_id" id="menu_id" class="form-select select2">
                                            <option value>Select a a Menu</option>
                                            @foreach($menus->toArray() as $val => $label)
                                                <option value="{{ $val }}" @if(old('menu_id', $item->menu_id) == $val) selected @endif>{{ $label }}</option>
                                            @endforeach
                                        </select>
                                    </div>

                                </div>
                            </div>

                            <div class="row">
                                <div class="col-12">
                                    <div class="form-group">
                                        <label for="description" class="form-label req">Description</label>
                                        <span
                                                class="help">@if(session()->has('errors')) {!! session()->get('errors')->first('description') !!}@endif</span>
                                        <textarea name="description" id="description" class="form-control">{{ old('description', $item->description) }}</textarea>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-12 mb-3">

                                    <label class="switch switch-square switch-lg switch-success">
                                        <input type="checkbox" class="switch-input" name="show_in_menu" value="yes" @if($item->exists and $item->show_in_menu == "yes") checked @endif />
                                        <span class="switch-toggle-slider">

                                            <span class="switch-on">
                                              <i class="bx bx-check"></i>
                                            </span>
                                            <span class="switch-off">
                                              <i class="bx bx-x"></i>
                                            </span>
                                        </span>

                                        <span class="switch-label">Show in Menu?</span>
                                    </label>

                                </div>
                            </div>

                            <div class="row">
                                <div class="col-12">
                                    <div class="alert alert-info border-0 rgr_configs">
                                        <h6><strong><u>Permission Routes Group</u></strong></h6>
                                        <p class="mb-3">Each permission must have at least one default route, please
                                            configure the route group for this permission.</p>

                                        @if($item->exists and $item->routes->count() > 0)
                                            <div class="rgr_cont">
                                                @foreach($item->routes as $proute)

                                                    <div class="row @if($loop->index > 0) new_route_form @endif">

                                                        <div class="col-3">
                                                            <div class="form-group">
                                                                <input type="text" name="title[]"
                                                                       value="{{ $proute->title }}" class="form-control"
                                                                       placeholder="Title of the route">
                                                            </div>
                                                        </div>

                                                        <div class="col-4">
                                                            <div class="form-group">
                                                                <input type="text" name="route[]"
                                                                       value="{{ $proute->route }}" class="form-control"
                                                                       placeholder="Name of the route">
                                                            </div>
                                                        </div>

                                                        <div class="col-2 text-right" style="padding-top: 8px; text-align: right">
                                                            <strong>Is Default?</strong>
                                                        </div>

                                                        <div class="col-1">
                                                            <div class="form-group">
                                                                <select name="is_default[]" class="form-select is_default_dd" style="width: 110%;">
                                                                    <option value="no" @if($proute->is_default === 'no') selected @endif>No</option>
                                                                    <option value="yes" @if($proute->is_default === 'yes') selected @endif>Yes</option>
                                                                </select>
                                                            </div>
                                                        </div>

                                                        <div class='col-1'><a class='btn btn-danger remove_route'><i
                                                                        class='bx bx-trash text-white'></i></a></div>

                                                    </div>

                                                @endforeach
                                            </div>

                                        @else
                                            <div class="rgr_cont">
                                                <div class="row">

                                                    <div class="col-3">
                                                        <div class="form-group">
                                                            <input type="text" name="title[]" class="form-control"
                                                                   placeholder="Title of the route">
                                                        </div>
                                                    </div>

                                                    <div class="col-4">
                                                        <div class="form-group">
                                                            <input type="text" name="route[]" class="form-control"
                                                                   placeholder="Name of the route">
                                                        </div>
                                                    </div>

                                                    <div class="col-2 text-right" style="padding-top: 8px; text-align: right">
                                                        <strong>Is Default?</strong>
                                                    </div>

                                                    <div class="col-1">
                                                        <div class="form-group">
                                                            <select name="is_default[]" class="form-control is_default_dd" style="width: 110%;">
                                                                <option value="no">No</option>
                                                                <option value="yes">Yes</option>
                                                            </select>
                                                        </div>
                                                    </div>

                                                </div>
                                            </div>
                                        @endif

                                        <div class="row">
                                            <div class="col-12">
                                                <a href="#" class="btn btn-primary new_route">
                                                    <i class="bx bx-bookmark-plus"></i> New route
                                                </a>
                                            </div>
                                        </div>

                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-12">

                                    <a href="{{ route('settings.my-permissions.list') }}" class="btn btn-warning">
                                        <i class="bx bx-arrow-back"></i> Back
                                    </a>

                                    <button type="submit" class="btn btn-success">
                                        <i class="bx bx-save"></i> Save
                                    </button>

                                </div>
                            </div>

                            </form>

                        </div>

                    </div>

                </div>

            </div>
            <!-- /traffic sources -->

        </div>
    </div>

@endsection
