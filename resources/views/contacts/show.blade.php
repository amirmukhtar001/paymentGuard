@extends('layouts.' . config('settings.active_layout'))

@section('content')
<div class="row">
    <div class="col-12">

        <div class="card">
            <div class="card-header header-elements-inline">
                <h5 class="card-title">{{ $title }}</h5>
                <div class="header-elements">
                    <a href="{{ route('settings.contacts.edit', $item->uuid) }}" class="btn btn-primary">
                        <i class="bx bx-edit"></i> Edit
                    </a>
                </div>
            </div>

            <div class="card-body">
                <dl class="row">
                    <dt class="col-sm-3">UUID</dt>
                    <dd class="col-sm-9">{{ $item->uuid }}</dd>

                    <dt class="col-sm-3">Company</dt>
                    <dd class="col-sm-9">
                        {{ optional($item->company)->title ?? optional($item->company)->title ?? '-' }}
                    </dd>

                    <dt class="col-sm-3">Name</dt>
                    <dd class="col-sm-9">{{ $item->name }}</dd>

                    <dt class="col-sm-3">Designation</dt>
                    <dd class="col-sm-9">{{ $item->designation->title ?? '-' }}</dd>

                    <dt class="col-sm-3">Department</dt>
                    <dd class="col-sm-9">{{ $item->department->name ?? '-' }}</dd>

                    <dt class="col-sm-3">Contact Number</dt>
                    <dd class="col-sm-9">{{ $item->contact_number ?? '-' }}</dd>

                    <dt class="col-sm-3">Ext No</dt>
                    <dd class="col-sm-9">{{ $item->ext_no ?? '-' }}</dd>

                    <dt class="col-sm-3">Fax Number</dt>
                    <dd class="col-sm-9">{{ $item->fax_number ?? '-' }}</dd>

                    <dt class="col-sm-3">Email Address</dt>
                    <dd class="col-sm-9">{{ $item->email_address ?? '-' }}</dd>

                    <dt class="col-sm-3">Office Address</dt>
                    <dd class="col-sm-9">{{ $item->office_address ?? '-' }}</dd>

                    <dt class="col-sm-3">Remarks</dt>
                    <dd class="col-sm-9">{{ $item->remarks ?? '-' }}</dd>

                    <dt class="col-sm-3">Primary Contact</dt>
                    <dd class="col-sm-9">{{ $item->is_primary ? 'Yes' : 'No' }}</dd>

                    <dt class="col-sm-3">Status</dt>
                    <dd class="col-sm-9">{{ ucfirst($item->status) }}</dd>

                    <dt class="col-sm-3">Sort Order</dt>
                    <dd class="col-sm-9">{{ $item->sort_order }}</dd>

                    <dt class="col-sm-3">Created At</dt>
                    <dd class="col-sm-9">{{optional($item->created_at)->format('d/m/Y') }}</dd>

                    <dt class="col-sm-3">Updated At</dt>
                    <dd class="col-sm-9">{{ optional($item->updated_at)->format('d/m/Y') }}</dd>
                </dl>

                <a href="{{ route('settings.contacts.list') }}" class="btn btn-secondary">
                    <i class="bx bx-arrow-back"></i> Back to list
                </a>
            </div>

        </div>

    </div>
</div>
@endsection