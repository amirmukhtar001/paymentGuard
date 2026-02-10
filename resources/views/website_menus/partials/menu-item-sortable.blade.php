{{-- resources/views/website_menus/partials/menu-item-sortable.blade.php --}}
<li class="menu-item" data-id="{{ $item->id }}">
    <div class="menu-item-header">
        <div class="menu-item-header-left">
            <span class="drag-handle" title="Drag to move">☰</span>
            <div>
                <span class="menu-item-title">{{ $item->label }}</span>
                <div class="menu-item-meta">
                    {{-- You can customize this label based on your DB schema if you store type --}}
                    Menu Item
                </div>
            </div>
        </div>
        <div class="menu-item-controls">
            <button type="button" class="btn btn-toggle collapsed toggle-item" title="Show more options">
                <i class="bx bx-down-arrow"></i>
            </button>
            <button type="button" class="btn btn-delete delete-item" title="Delete item">
                <i class="fas fa-trash-alt"></i>
            </button>
        </div>
    </div>

    <div class="menu-item-body">
        <div class="menu-item-body-inner">
            <div class="row">
                <div class="col-md-12 mb-2">
                    <label class="form-label">Navigation Label</label>
                    <input type="text"
                        class="form-control form-control-sm item-label"
                        value="{{ $item->label }}"
                        placeholder="Menu item name">
                </div>
            </div>
            <div class="row">
                <div class="col-md-12 mb-2">
                    <label class="form-label">URL</label>
                    <input type="text"
                        class="form-control form-control-sm item-url"
                        value="{{ $item->url }}"
                        placeholder="https://example.com">
                </div>
            </div>
            <div class="row">
                <div class="col-md-12 mb-2">
                    <label class="form-label">Title / Tooltip</label>

                    <input type="text"
                        class="form-control form-control-sm item-description"
                        value="{{ $item->description }}"
                        placeholder="Optional description or tooltip text">

                </div>
            </div>
            <div class="row">
                <div class="col-md-6 mb-2">
                    <label class="form-label">Open Link In</label>
                    <select class="form-select form-select-sm item-target">
                        <option value="_self" {{ $item->target === '_self' ? 'selected' : '' }}>Same Window</option>
                        <option value="_blank" {{ $item->target === '_blank' ? 'selected' : '' }}>New Tab</option>
                    </select>
                </div>
                <div class="col-md-6 mb-2">
                    <label class="form-label">Status</label>
                    <select class="form-select form-select-sm item-status">
                        <option value="active" {{ $item->status === 'active' ? 'selected' : '' }}>Active</option>
                        <option value="inactive" {{ $item->status === 'inactive' ? 'selected' : '' }}>Inactive</option>
                    </select>
                </div>
            </div>
            <div class="row mt-1">
                <div class="col-12 d-flex justify-content-between align-items-center">
                    <small class="text-muted">
                        Drag to move. Drag right to make it a sub-item.
                    </small>
                    <button type="button" class="btn btn-link text-danger p-0 small item-remove-link">Remove</button>
                </div>
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