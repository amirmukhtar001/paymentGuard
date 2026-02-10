{{-- resources/views/website_menus/partials/menu-item-sortable.blade.php --}}
<li class="menu-item" data-id="{{ $item->id }}">
    <div class="menu-item-header">
        <div class="d-flex align-items-center">
            <span class="drag-handle me-2">☰</span>
            <span class="menu-item-title">{{ $item->label }}</span>
        </div>
        <div class="menu-item-controls">
            <button type="button" class="btn btn-toggle collapsed toggle-item" title="Edit">
                <i class="fas fa-edit"></i>
            </button>
            <button type="button" class="btn btn-delete delete-item" title="Delete">
                <i class="fas fa-trash-alt"></i>
            </button>
        </div>
    </div>
    <div class="menu-item-body">
        <div class="row">
            <div class="col-md-12 mb-2">
                <label class="form-label">Navigation Label</label>
                <input type="text" class="form-control form-control-sm item-label" value="{{ $item->label }}" placeholder="Menu item name">
            </div>
        </div>
        <div class="row">
            <div class="col-md-12 mb-2">
                <label class="form-label">URL</label>
                <input type="text" class="form-control form-control-sm item-url" value="{{ $item->url }}" placeholder="https://example.com">
            </div>
        </div>
        <div class="row">
            <div class="col-md-12 mb-2">
                <label class="form-label">Description</label>
                <textarea class="form-control form-control-sm item-description" rows="3" placeholder="Optional description or tooltip text">{{ $item->description }}</textarea>
            </div>
        </div>
        <div class="row">
            <div class="col-md-6 mb-2">
                <label class="form-label">Open Link In</label>
                <select class="form-control form-control-sm item-target">
                    <option value="_self" {{ $item->target === '_self' ? 'selected' : '' }}>Same Window</option>
                    <option value="_blank" {{ $item->target === '_blank' ? 'selected' : '' }}>New Tab</option>
                </select>
            </div>
            <div class="col-md-6 mb-2">
                <label class="form-label">Status</label>
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
        @include('website_menus.partials.menu-item-sortable', ['item' => $child])
        @endforeach
        @endif
    </ol>
</li>