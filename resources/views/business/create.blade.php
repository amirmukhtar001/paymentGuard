@extends('layouts.' . config('settings.active_layout'))

@section('title', 'Create business')

@section('content')
    <div class="row justify-content-center">
        <div class="col-md-6 col-lg-5">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Create your business</h5>
                    <small class="text-muted">
                        This will be the main entity for your cash reconciliation and branches.
                    </small>
                </div>
                <div class="card-body">
                    <form method="POST" action="{{ route('business.store') }}">
                        @csrf
                        <div class="mb-3">
                            <label for="name" class="form-label">Business name</label>
                            <input type="text" name="name" id="name" value="{{ old('name') }}" required
                                class="form-control @error('name') is-invalid @enderror">
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="mb-3">
                            <label for="business_type" class="form-label">Type</label>
                            <select name="business_type" id="business_type" class="form-select">
                                <option value="restaurant" {{ old('business_type') === 'restaurant' ? 'selected' : '' }}>
                                    Restaurant
                                </option>
                                <option value="clinic" {{ old('business_type') === 'clinic' ? 'selected' : '' }}>Clinic</option>
                                <option value="retail" {{ old('business_type') === 'retail' ? 'selected' : '' }}>Retail</option>
                                <option value="salon" {{ old('business_type') === 'salon' ? 'selected' : '' }}>Salon</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="timezone" class="form-label">Timezone</label>
                            <input type="text" name="timezone" id="timezone"
                                value="{{ old('timezone', 'Asia/Karachi') }}" class="form-control">
                            <small class="form-text text-muted">
                                Use a valid timezone identifier (e.g. <code>Asia/Karachi</code>).
                            </small>
                        </div>
                        <button type="submit" class="btn btn-primary w-100">
                            Create business
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
