@extends('layouts.' . config('settings.active_layout'))

@section('content')
<div class="row">
    <div class="col-12">

        <div class="card">
            <div class="card-header header-elements-inline">
                <h5 class="card-title">View Tender</h5>
                <div class="header-elements">
                    <a href="{{ route('settings.tenders.edit', $tender->uuid) }}" class="btn btn-sm btn-primary">
                        <i class="bx bx-edit"></i> Edit
                    </a>
                    <a href="{{ route('settings.tenders.list') }}" class="btn btn-sm btn-secondary">
                        <i class="bx bx-arrow-back"></i> Back
                    </a>
                </div>
            </div>

            <div class="card-body">

                {{-- Basic Info --}}
                <div class="row mb-3">
                    <div class="col-md-8">
                        <h4 class="mb-1">
                            {{ $tender->title }}
                        </h4>

                        @php
                        $statusValue = $tender->status?->value ?? $tender->status;
                        $statusKey = strtolower($statusValue ?? '');
                        $statusClass = match ($statusKey) {
                        'active', 'open' => 'badge bg-success',
                        'draft' => 'badge bg-secondary',
                        'closed' => 'badge bg-danger',
                        'inactive' => 'badge bg-warning',
                        default => 'badge bg-light text-dark',
                        };

                        $typeValue = $tender->tender_type?->value ?? $tender->tender_type;
                        $typeClass = 'badge bg-info';
                        @endphp

                        <div class="mb-2">
                            @if($statusValue)
                            <span class="{{ $statusClass }} me-1">{{ $statusValue }}</span>
                            @endif

                            @if($typeValue)
                            <span class="{{ $typeClass }}">{{ $typeValue }}</span>
                            @endif
                        </div>

                        @if($tender->tender_number)
                        <div class="text-muted">
                            <strong>Tender No:</strong> {{ $tender->tender_number }}
                        </div>
                        @endif

                        @if($tender->company)
                        <div class="text-muted">
                            <strong>Web Site:</strong> {{ $tender->company->title ?? $tender->company->name }}
                        </div>
                        @endif
                    </div>

                    {{-- Main Media Preview --}}
                    <div class="col-md-4 text-md-end mt-3 mt-md-0">
                        @if($tender->media)
                        @php
                        $media = $tender->media;
                        $ext = strtolower($media->extension ?? '');
                        $url = $media->file_path ? asset('storage/' . $media->file_path) : null;
                        $isImage = in_array($ext, ['jpg','jpeg','png','gif','webp','svg']);
                        @endphp

                        @if($url)
                        @if($isImage)
                        <img src="{{ $url }}"
                            alt="{{ $media->title }}"
                            class="img-thumbnail mb-2"
                            style="max-width: 200px; max-height: 200px; object-fit: cover;">
                        @else
                        <a href="{{ $url }}" target="_blank" rel="noopener">
                            {{ $media->title ?? 'View file' }} ({{ strtoupper($ext) }})
                        </a>
                        @endif
                        @else
                        <span class="text-muted">No media file path</span>
                        @endif
                        @else
                        <span class="text-muted">No media attached</span>
                        @endif
                    </div>
                </div>

                <hr>

                {{-- Dates --}}
                <div class="row mb-3">
                    <div class="col-md-4">
                        <h6 class="text-muted">Date of Advertisement</h6>
                        <p>{{ optional($tender->date_of_advertisement)->format('Y-m-d') ?? '-' }}</p>
                    </div>
                    <div class="col-md-4">
                        <h6 class="text-muted">Closing Date</h6>
                        <p>{{ optional($tender->closing_date)->format('Y-m-d') ?? '-' }}</p>
                    </div>
                    <div class="col-md-4">
                        <h6 class="text-muted">Created / Updated</h6>
                        <p class="mb-0">
                            <strong>Created:</strong>
                            {{ optional($tender->created_at)->format('Y-m-d H:i') ?? '-' }}
                        </p>
                        <p class="mb-0">
                            <strong>Updated:</strong>
                            {{ optional($tender->updated_at)->format('Y-m-d H:i') ?? '-' }}
                        </p>
                    </div>
                </div>

                <hr>

                {{-- Bidding Document --}}
                <div class="row mb-3">
                    <div class="col-md-12">
                        <h6 class="text-muted">Bidding Document</h6>

                        @if($tender->biddingDocumentMedia)
                        @php
                        $doc = $tender->biddingDocumentMedia;
                        $ext = strtolower($doc->extension ?? '');
                        $url = $doc->file_path ? asset('storage/' . $doc->file_path) : null;
                        @endphp

                        @if($url)
                        <a href="{{ $url }}" target="_blank" rel="noopener">
                            {{ $doc->title ?? 'View bidding document' }} ({{ strtoupper($ext) }})
                        </a>
                        @else
                        <span class="text-muted">No file path.</span>
                        @endif
                        @else
                        <span class="text-muted">No bidding document attached.</span>
                        @endif
                    </div>
                </div>

                <hr>

                {{-- Description --}}
                <div class="row">
                    <div class="col-md-12">
                        <h6 class="text-muted">Description</h6>
                        <div class="border rounded p-3 bg-light">
                            @if($tender->description)
                            {!! nl2br(e($tender->description)) !!}
                            @else
                            <span class="text-muted">No description provided.</span>
                            @endif
                        </div>
                    </div>
                </div>

            </div>
        </div>

    </div>
</div>
@endsection