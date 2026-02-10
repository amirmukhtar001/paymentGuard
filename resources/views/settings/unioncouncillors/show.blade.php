@extends('layouts.'.config('settings.active_layout'))

@section('content')
<div class="row">
    <!-- Website Analytics-->
    <div class="col-12 mb-4">
        <div class="card">
            <div class="card-header">
                <h4 class="card-title">Show UC's/ NC's</h4>
            </div>
            <div class="card-body">
                <div class="mb-3">
                    <label for="name" class="form-label">Name</label>
                    <p class="form-control-static">{{ $model->name }}</p>
                </div>
                <div class="mb-3">
                    <label for="type" class="form-label">Type</label>
                    <p class="form-control-static">{{ $model->type }}</p>
                </div>
                <div class="mb-3">
                    <label for="district" class="form-label">District</label>
                    <p class="form-control-static">{{ $model->district->title ?? '' }}</p>
                </div>
                <div class="mb-3">
                    <label for="tehsil" class="form-label">Tehsil</label>
                    <p class="form-control-static">{{ $model->tehsil->title ?? '' }}</p>
                </div>

                <div class="row">
                    <div class="col-12">

                        <a href="{{ route('settings.vc.list') }}" class="btn btn-warning">
                            <i class="bx bx-arrow-back"></i> Back
                        </a>

                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    // No scripts needed for the "show" view
</script>
@endpush