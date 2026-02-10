{{-- resources/views/website_menus/partials/menu-item.blade.php --}}
<li class="menu-item" data-id="{{ $item->id }}">
    <div class="menu-item-header">
        <span class="drag-handle me-2">☰</span>
        <span class="menu-item-title">{{ $item->label }}</span>
        <div class="menu-item-controls">
            <button type="button" class="btn btn-sm btn-link btn-icon toggle-item" title="Edit">
                <i class="bi bi-chevron-down"></i>
            </button>
            <button type="button" class="btn btn-sm btn-link btn-icon text-danger delete-item" title="Delete">
                <i class="bi bi-trash"></i>
            </button>
        </div>
    </div>
    <div class="menu-item-body">
        <div class="row">
            <div class="col-md-6 mb-2">
                <label class="form-label small">Label</label>
                <input type="text" class="form-control form-control-sm item-label" value="{{ $item->label }}">
            </div>
            <div class="col-md-6 mb-2">
                <label class="form-label small">URL</label>
                <input type="text" class="form-control form-control-sm item-url" value="{{ $item->url }}">
            </div>
        </div>
        <div class="mb-2">
            <label class="form-label small">Description (optional)</label>
            <textarea class="form-control form-control-sm item-description" rows="2">{{ $item->description }}</textarea>
        </div>
        <div class="row">
            <div class="col-md-6 mb-2">
                <label class="form-label small">Target</label>
                <select class="form-control form-control-sm item-target">
                    <option value="_self" {{ $item->target === '_self' ? 'selected' : '' }}>Same Window (_self)</option>
                    <option value="_blank" {{ $item->target === '_blank' ? 'selected' : '' }}>New Window (_blank)</option>
                </select>
            </div>
            <div class="col-md-6 mb-2">
                <label class="form-label small">Status</label>
                <select class="form-control form-control-sm item-status">
                    <option value="active" {{ $item->status === 'active' ? 'selected' : '' }}>Active</option>
                    <option value="inactive" {{ $item->status === 'inactive' ? 'selected' : '' }}>Inactive</option>
                </select>
            </div>
        </div>
    </div>
    <ol>
        @if($item->children && $item->children->count() > 0)
        @foreach($item->children->sortBy('sort_order') as $child)
        @include('website_menus.partials.menu-item', ['item' => $child])
        @endforeach
        @endif
    </ol>
</li>