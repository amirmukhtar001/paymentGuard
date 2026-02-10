@extends('layouts.' . config('settings.active_layout'))

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="card-title mb-0">{{ $title }}</h5>
                <div>
                    <a href="{{ route('settings.website-sections.edit', ['website_section' => $item->uuid]) }}" class="btn btn-primary btn-sm">
                        <i class="bx bx-edit"></i> Edit
                    </a>
                    <a href="{{ route('settings.website-sections.list') }}" class="btn btn-secondary btn-sm">
                        <i class="bx bx-arrow-back"></i> Back
                    </a>
                </div>
            </div>
            <div class="card-body">
                <div class="row mb-4">
                    <div class="col-md-4">
                        <div class="mb-3">
                            <h6 class="text-muted">Title</h6>
                            <p class="mb-0">{{ $item->title }}</p>
                        </div>
                        <div class="mb-3">
                            <h6 class="text-muted">Section Type</h6>
                            <span class="badge bg-label-info">{{ ucfirst($item->section_type) }}</span>
                        </div>
                        <div class="mb-3">
                            <h6 class="text-muted">Status</h6>
                            <span class="badge bg-label-{{ $item->status === 'active' ? 'success' : ($item->status === 'draft' ? 'warning' : 'secondary') }}">
                                {{ ucfirst($item->status) }}
                            </span>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="mb-3">
                            <h6 class="text-muted">Website</h6>
                            <p class="mb-0">{{ optional($item->company)->title ?? '-' }}</p>
                        </div>
                        <div class="mb-3">
                        </div>
                        <div class="mb-3">
                            <h6 class="text-muted">Sort Order</h6>
                            <p class="mb-0">{{ $item->sort_order }}</p>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="mb-3">
                            <h6 class="text-muted">Featured</h6>
                            <p class="mb-0">{{ $item->is_featured ? 'Yes' : 'No' }}</p>
                        </div>
                        <div class="mb-3">
                            <h6 class="text-muted">Device Visibility</h6>
                            <div class="d-flex flex-wrap gap-2">
                                @if($item->show_on_mobile)
                                    <span class="badge bg-label-primary"><i class="bx bx-mobile"></i> Mobile</span>
                                @else
                                    <span class="badge bg-label-secondary"><i class="bx bx-mobile"></i> Mobile (Hidden)</span>
                                @endif
                                @if($item->show_on_tablet)
                                    <span class="badge bg-label-info"><i class="bx bx-tablet"></i> Tablet</span>
                                @else
                                    <span class="badge bg-label-secondary"><i class="bx bx-tablet"></i> Tablet (Hidden)</span>
                                @endif
                                @if($item->show_on_desktop)
                                    <span class="badge bg-label-success"><i class="bx bx-desktop"></i> Desktop</span>
                                @else
                                    <span class="badge bg-label-secondary"><i class="bx bx-desktop"></i> Desktop (Hidden)</span>
                                @endif
                            </div>
                        </div>
                        <div class="mb-3">
                            <h6 class="text-muted">Publish Window</h6>
                            <p class="mb-0">
                                @if($item->published_at)
                                    From {{ $item->published_at->format('d M Y, H:i') }}
                                @else
                                    Not set
                                @endif
                                <br>
                                @if($item->expires_at)
                                    Until {{ $item->expires_at->format('d M Y, H:i') }}
                                @endif
                            </p>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <h6 class="text-muted">Heading</h6>
                        <p>{{ $item->heading ?? '-' }}</p>
                        <h6 class="text-muted mt-3">Subheading</h6>
                        <p>{{ $item->subheading ?? '-' }}</p>
                        <h6 class="text-muted mt-3">Button</h6>
                        <p>
                            {{ $item->button_text ?? '-' }}<br>
                            <small class="text-muted">{{ $item->button_link }}</small>
                        </p>
                    </div>
                    <div class="col-md-6">
                        <h6 class="text-muted">Body Content</h6>
                        <div class="border p-3 rounded bg-light" style="min-height: 150px;">
                            {!! $item->body_content ?? '<span class="text-muted">No content</span>' !!}
                        </div>
                    </div>
                </div>

                <div class="row mt-4">
                    <div class="col-md-6">
                        <h6 class="text-muted">Background Image</h6>
                        @if($item->background_image_url || optional($item->backgroundImage)->file_path)
                            <img src="{{ $item->background_image_url ?? asset('storage/' . $item->backgroundImage->file_path) }}" class="img-fluid rounded" alt="{{ $item->title }}">
                        @else
                            <p class="text-muted">No background image</p>
                        @endif
                    </div>
                    <div class="col-md-6">
                        <h6 class="text-muted">Featured Image</h6>
                        @if($item->featured_image_url || optional($item->featuredImage)->file_path)
                            <img src="{{ $item->featured_image_url ?? asset('storage/' . $item->featuredImage->file_path) }}" class="img-fluid rounded" alt="{{ $item->title }}">
                        @else
                            <p class="text-muted">No featured image</p>
                        @endif
                    </div>
                </div>

                <div class="row mt-4">
                    <div class="col-12">
                        <h6 class="text-muted">Settings</h6>
                        @if(!empty($item->settings))
                            <pre class="bg-light p-3 rounded">{{ json_encode($item->settings, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES) }}</pre>
                        @else
                            <p class="text-muted">No custom settings.</p>
                        @endif
                    </div>
                </div>

                <div class="row mt-3">
                    <div class="col-md-6">
                        <h6 class="text-muted">Content Blocks</h6>
                        @if(!empty($item->content_blocks))
                            <pre class="bg-light p-3 rounded">{{ json_encode($item->content_blocks, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES) }}</pre>
                        @else
                            <p class="text-muted">No content blocks configured.</p>
                        @endif
                    </div>
                    <div class="col-md-6">
                        <h6 class="text-muted">Custom Classes</h6>
                        @if(!empty($item->custom_classes))
                            <pre class="bg-light p-3 rounded">{{ json_encode($item->custom_classes, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES) }}</pre>
                        @else
                            <p class="text-muted">No custom classes set.</p>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
