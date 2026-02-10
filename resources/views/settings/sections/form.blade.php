@extends('layouts.'.config('settings.active_layout'))

@push('scripts')
    <script src="{{ asset('assets/js/plugins/forms/selects/select2.min.js') }}"></script>
    <script type="text/javascript">
        $(document).ready(function () {
            $(".select2").select2();
            @if($item->exists && $item->parent_id)
                $("#parent_id").val('{{ $item->parent_id }}').trigger('change');
            @endif
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

                            <form method="POST" action="{{ $item->exists ? route('settings.sections.update', \Illuminate\Support\Facades\Crypt::encrypt($item->id)) : route('settings.sections.store') }}" enctype="multipart/form-data">
                                @csrf
                                @if($item->exists)
                                    @method('PUT')
                                @endif

                            <div class="row">
                                <div class="col-12">
                                    <div class="form-group">
                                        <label for="title" class="form-label req">{{ config('settings.section_title') }} Name</label>
                                        <span class="help">@if(session()->has('errors')) {!! session()->get('errors')->first('title') !!}@endif</span>
                                        <input type="text" name="title" id="title" class="form-control" value="{{ old('title', $item->title ?? '') }}" required>
                                    </div>
                                </div>
                            </div>

                            <div class="row">

                                <div class="col-12">
                                    <div class="form-group">
                                        <label for="parent_id" class="control-label">Select a Parent {{ config('settings.section_title') }}</label>
                                        <span class="help">@if(Session::has('errors')) {!! Session::get('errors')->first('parent_id') !!} @endif</span>
                                        <select name="parent_id" id="parent_id" class="form-select select2">
                                            <option value="">This is Parent {{ config('settings.section_title') }}</option>
                                            @foreach($parent_sections as $key => $title)
                                                <option value="{{ $key }}" {{ old('parent_id', $item->parent_id ?? '') == $key ? 'selected' : '' }}>
                                                    {{ $title }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-12">
                                    <div class="form-group">
                                        <label for="description" class="form-label req">Details</label>
                                        <span class="help">@if(session()->has('errors')) {!! session()->get('errors')->first('description') !!}@endif</span>
                                        <textarea name="description" id="description" class="form-control">{{ old('description', $item->description ?? '') }}</textarea>
                                    </div>
                                </div>
                            </div>

                            {{-- File Upload Section --}}
                            <div class="row">
                                <div class="col-12">
                                    <div class="form-group">
                                        <label class="form-label">Attachments</label>
                                        <small class="text-muted d-block mb-2">Upload supporting documents, images, or other files related to this {{ config('settings.section_title') }}</small>

                                        @if($item->exists)
                                            {{-- For editing existing records --}}
                                            <livewire:media-uploader
                                                :for="$item"
                                                collection="attachments"
                                                :multiple="true"
                                                accept="image/jpeg,image/png,image/jpg,image/gif,image/webp,application/pdf,application/msword,application/vnd.openxmlformats-officedocument.wordprocessingml.document,application/vnd.ms-excel,application/vnd.openxmlformats-officedocument.spreadsheetml.sheet,text/csv"
                                                label="Upload Files"
                                            />
                                        @else
                                            {{-- For creating new records - deferred upload --}}
                                            <livewire:media-uploader
                                                model="section"
                                                collection="attachments"
                                                :multiple="true"
                                                accept="image/jpeg,image/png,image/jpg,image/gif,image/webp,application/pdf,application/msword,application/vnd.openxmlformats-officedocument.wordprocessingml.document,application/vnd.ms-excel,application/vnd.openxmlformats-officedocument.spreadsheetml.sheet,text/csv"
                                                label="Upload Files"
                                                :aliases="['section' => '\App\Models\Section']"
                                            />
                                        @endif
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-12">

                                    <a href="{{ route('settings.sections.list') }}" class="btn btn-warning">
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
