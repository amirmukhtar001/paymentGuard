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

                        <form enctype="multipart/form-data" method="POST" action="{{ route('settings.vc.import.save') }}">
                            @csrf

                        <div class="row">
                            <div class="col-12">
                                <div class="alert alert-info">
                                    <h6><i class="bx bx-info-circle"></i> CSV Format Information</h6>
                                    <p>The CSV file supports two formats:</p>
                                    <ul>
                                        <li><strong>Standard Format (6 columns):</strong> ID, Division, District, Tehsil, Union_Council, Village_Council</li>
                                        <li><strong>Extended Format with Urdu (11 columns):</strong> ID, Division, Division_Urdu, District, District_Urdu, Tehsil, Tehsil_Urdu, Union_Council, Union_Council_Urdu, Village_Council, Village_Council_Urdu</li>
                                    </ul>
                                    <p>
                                        <a href="{{ asset('sample_village_import.csv') }}" class="btn btn-sm btn-outline-primary" download>
                                            <i class="bx bx-download"></i> Download Sample CSV File
                                        </a>
                                    </p>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-6">
                                <div class="form-group">
                                    <label for="upload_file" class="form-label req">File CSV</label>
                                    <span class="help">@if(session()->has('errors')) {!! session()->get('errors')->first('upload_file') !!}@endif</span>
                                    <input type="file" name="upload_file" class="form-control" id="upload_file" required>
                                </div>
                            </div>

                        </div>

                        <div class="row">
                            <div class="col-12">

                                <a href="{{ route('settings.vc.list') }}" class="btn btn-warning">
                                    <i class="bx bx-arrow-back"></i> Back
                                </a>

                                <button type="submit" class="btn btn-success">
                                    <i class="bx bx-save"></i> Import
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
