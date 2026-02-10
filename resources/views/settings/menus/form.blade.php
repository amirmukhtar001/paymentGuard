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
                                    ? route('settings.menus.update', \Illuminate\Support\Facades\Crypt::encrypt($item->id))
                                    : route('settings.menus.store');
                            @endphp

                            <form enctype="multipart/form-data" method="POST" action="{{ $actionUrl }}">
                                @csrf
                                @if($item->exists)
                                    @method('PUT')
                                @endif

                            <div class="row">
                                <div class="col-6">
                                    <div class="form-group">
                                        <label for="title" class="form-label req">Menu Title</label>
                                        <span
                                            class="help">@if(session()->has('errors')) {!! session()->get('errors')->first('title') !!}@endif</span>
                                        <input type="text" name="title" id="title" class="form-control" required value="{{ old('title', $item->title) }}">
                                    </div>
                                </div>

                                <div class="col-6">
                                    <div class="row">

                                        <div class="col-12">
                                            <div class="form-group">
                                                <label for="parent_id" class="control-label">Select parent</label>

                                                <span
                                                    class="help">@if(Session::has('errors')) {!! Session::get('errors')->first('parent_id') !!} @endif</span>
                                                <select name="parent_id" id="parent_id" class="form-select select2">
                                                    <option value>This is parent</option>
                                                    @foreach($menus_parents->toArray() as $val => $label)
                                                        <option value="{{ $val }}" @if(old('parent_id', $item->parent_id) == $val) selected @endif>{{ $label }}</option>
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
                                        <label for="description" class="form-label req">Description</label>
                                        <span
                                            class="help">@if(session()->has('errors')) {!! session()->get('errors')->first('description') !!}@endif</span>
                                        <textarea name="description" id="description" class="form-control">{{ old('description', $item->description) }}</textarea>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-6">
                                    <div class="form-group">
                                        <label for="icon" class="form-label req">Menu Icon</label>
                                        <span
                                            class="help">@if(session()->has('errors')) {!! session()->get('errors')->first('icon') !!}@endif</span>
                                        <input type="text" name="icon" id="icon" class="form-control" required value="{{ old('icon', $item->icon) }}">
                                    </div>
                                </div>

                                <div class="col-6">
                                    <div class="form-group">
                                        <label for="order" class="form-label req">Menu Order</label>
                                        <span
                                            class="help">@if(session()->has('errors')) {!! session()->get('errors')->first('order') !!}@endif</span>
                                        <input type="number" name="order" id="order" class="form-control" required value="{{ old('order', $item->order) }}">
                                    </div>
                                </div>

                                <div class="col-md-4 mb-3">

                                    <label class="switch switch-square switch-lg switch-success">
                                        <input type="checkbox" class="switch-input" name="is_collapsible" @if($item->exists and $item->is_collapsible == "yes") checked @endif />
                                        <span class="switch-toggle-slider">

                                            <span class="switch-on">
                                              <i class="bx bx-check"></i>
                                            </span>
                                            <span class="switch-off">
                                              <i class="bx bx-x"></i>
                                            </span>
                                        </span>

                                        <span class="switch-label">Is Collapsible?</span>
                                    </label>

                                </div>
                            </div>

                            {{-- <div class="row"> --}}
                            {{-- </div> --}}

                            <div class="row">
                                <div class="col-12">

                                    <a href="{{ route('settings.menus.list') }}" class="btn btn-warning">
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
