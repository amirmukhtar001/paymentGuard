@extends('layouts.'.config('settings.active_layout'))

@push('scripts')
    <script type="text/javascript">
        $(document).ready(function () {

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
                                    ? route('settings.company-types.update', \Illuminate\Support\Facades\Crypt::encrypt($item->id))
                                    : route('settings.company-types.store');
                            @endphp

                            <form enctype="multipart/form-data" method="POST" action="{{ $actionUrl }}">
                                @csrf
                                @if($item->exists)
                                    @method('PUT')
                                @endif

                                <div class="row">
                                    <div class="col-12">
                                        <div class="form-group">
                                            <label for="title" class="form-label req">Title</label>
                                            <span class="help">@if(session()->has('errors')) {!! session()->get('errors')->first('title') !!}@endif</span>
                                            <input type="text" name="title" id="title" class="form-control" required value="{{ old('title', $item->title) }}">
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-12">
                                        <div class="form-group">
                                            <label for="description" class="form-label">Description</label>
                                            <span class="help">@if(session()->has('errors')) {!! session()->get('errors')->first('description') !!}@endif</span>
                                            <textarea name="description" id="description" class="form-control" rows="3">{{ old('description', $item->description) }}</textarea>
                                        </div>
                                    </div>
                                </div>

                            <div class="row">
                                <div class="col-12">

                                    <a href="{{ route('settings.company-types.list') }}" class="btn btn-warning">
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
