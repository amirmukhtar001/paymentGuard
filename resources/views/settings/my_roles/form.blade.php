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
                                    ? route('settings.my-roles.update', \Illuminate\Support\Facades\Crypt::encrypt($item->id))
                                    : route('settings.my-roles.store');
                            @endphp

                            <form enctype="multipart/form-data" method="POST" action="{{ $actionUrl }}">
                                @csrf
                                @if($item->exists)
                                    @method('PUT')
                                @endif

                            <div class="row">
                                <div class="col-12">
                                    <div class="form-group">
                                        <label for="name" class="form-label req">Role Title</label>
                                        <span
                                            class="help">@if(session()->has('errors')) {!! session()->get('errors')->first('name') !!}@endif</span>
                                        <input type="text" name="name" id="name" class="form-control" required value="{{ old('name', $item->name) }}">
                                    </div>
                                </div>
                            </div>

                            <div class="row">

                                <div class="col-6">
                                    <div class="form-group">
                                        <label for="slug" class="form-label req">Role Slug</label>
                                        <span
                                            class="help">@if(session()->has('errors')) {!! session()->get('errors')->first('slug') !!}@endif</span>
                                        <input type="text" name="slug" id="slug" class="form-control" required value="{{ old('slug', $item->slug) }}">
                                    </div>
                                </div>

                                <div class="col-6">
                                    <div class="form-group">
                                        <label for="level" class="form-label req">Role Level</label>
                                        <span
                                            class="help">@if(session()->has('errors')) {!! session()->get('errors')->first('level') !!}@endif</span>
                                        <input type="number" name="level" id="level" class="form-control" required min="0" value="{{ old('level', $item->level) }}">
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-12">
                                    <div class="form-group">
                                        <label for="description" class="form-label">Details</label>
                                        <span
                                            class="help">@if(session()->has('errors')) {!! session()->get('errors')->first('description') !!}@endif</span>
                                        <textarea name="description" id="description" class="form-control">{{ old('description', $item->description) }}</textarea>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-12">

                                    <a href="{{ route('settings.my-roles.list') }}" class="btn btn-warning">
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
