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
                                    ? route('settings.my-apps.update', \Illuminate\Support\Facades\Crypt::encrypt($item->id))
                                    : route('settings.my-apps.store');
                            @endphp

                            <form enctype="multipart/form-data" method="POST" action="{{ $actionUrl }}">
                                @csrf
                                @if($item->exists)
                                    @method('PUT')
                                @endif

                            <div class="row">
                                <div class="col-12">
                                    <div class="form-group">
                                        <label for="title" class="form-label req">App Title</label>
                                        <span
                                            class="help">@if(session()->has('errors')) {!! session()->get('errors')->first('title') !!}@endif</span>
                                        <input type="text" name="title" id="title" class="form-control" required value="{{ old('title', $item->title) }}">
                                    </div>
                                </div>
                            </div>

                            <div class="row">

                                <div class="col-6">
                                    <div class="form-group">
                                        <label for="route" class="form-label req">App Route</label>
                                        <span
                                            class="help">@if(session()->has('errors')) {!! session()->get('errors')->first('route') !!}@endif</span>
                                        <input type="text" name="route" id="route" class="form-control" required value="{{ old('route', $item->route) }}">
                                    </div>
                                </div>

                                <div class="col-6">
                                    <div class="form-group">
                                        <label for="icon" class="form-label req">App Icon</label>
                                        <span
                                            class="help">@if(session()->has('errors')) {!! session()->get('errors')->first('icon') !!}@endif</span>
                                        <input type="text" name="icon" id="icon" class="form-control" required value="{{ old('icon', $item->icon) }}">
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
                                <div class="col-12">

                                    <a href="{{ route('settings.my-apps.list') }}" class="btn btn-warning">
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
