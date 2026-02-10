{{-- resources/views/website_menus/form.blade.php --}}
@extends('layouts.' . config('settings.active_layout'))

@section('content')
<div class="row">
    <div class="col-12">
        <form id="website-menu-form"
            method="POST"
            action="{{ $menu->exists ? route('website-menus.update', $menu->uuid) : route('website-menus.store') }}">
            @csrf
            @if($menu->exists)
                @method('PUT')
            @endif

            {{-- Main Menu Settings --}}
            <div class="card mb-3">
                <div class="card-header">
                    <h5 class="mb-0">Website Menu <small class="text-muted">{{ $menu->exists ? 'Edit' : 'Create' }}</small></h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label class="form-label">Website <span class="text-danger">*</span></label>
                                <select name="company_id" class="form-control" required>
                                    <option value="">Select Website</option>
                                    @foreach($websites as $id => $name)
                                        <option value="{{ $id }}" {{ old('company_id', $menu->company_id) == $id ? 'selected' : '' }}>
                                            {{ $name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="mb-3">
                                <label class="form-label">Menu Type <span class="text-danger">*</span></label>
                                <select name="menu_type_id" class="form-control" required>
                                    <option value="">Select Menu Type</option>
                                    @foreach($menuTypes as $id => $name)
                                        <option value="{{ $id }}" {{ old('menu_type_id', $menu->menu_type_id) == $id ? 'selected' : '' }}>
                                            {{ $name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="mb-3">
                                <label class="form-label">Menu Title <span class="text-danger">*</span></label>
                                <input type="text" name="title" class="form-control" value="{{ old('title', $menu->title) }}" required>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label class="form-label">Status</label>
                                @php $status = old('status', $menu->status ?? 'active'); @endphp
                                <select name="status" class="form-control">
                                    <option value="active" {{ $status === 'active' ? 'selected' : '' }}>Active</option>
                                    <option value="inactive" {{ $status === 'inactive' ? 'selected' : '' }}>Inactive</option>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                {{-- LEFT SIDE: Add Menu Items --}}
                <div class="col-md-4">
                    {{-- Custom Links --}}
                    <div class="card mb-3">
                        <div class="card-header">
                            <strong>Custom Links</strong>
                        </div>
                        <div class="card-body">
                            <div class="mb-2">
                                <label class="form-label small">Label</label>
                                <input type="text" id="custom_label" class="form-control form-control-sm" placeholder="Menu Label">
                            </div>
                            <div class="mb-2">
                                <label class="form-label small">URL</label>
                                <input type="text" id="custom_url" class="form-control form-control-sm" placeholder="https://example.com">
                            </div>
                            <button type="button" id="btn-add-custom" class="btn btn-sm btn-primary w-100">
                                Add to Menu
                            </button>
                        </div>
                    </div>

                    {{-- Pages --}}
                    <div class="card mb-3">
                        <div class="card-header d-flex justify-content-between align-items-center">
                            <strong>Pages</strong>
                            <span class="badge bg-secondary" id="page-count">0 selected</span>
                        </div>
                        <div class="card-body p-2" style="max-height:300px; overflow-y:auto;">
                            <div id="pages-list">
                                @forelse($pages as $page)
                                    <div class="form-check mb-1">
                                        <input class="form-check-input page-checkbox" type="checkbox" 
                                               value="{{ $page->id }}"
                                               id="page_{{ $page->id }}"
                                               data-label="{{ $page->title }}"
                                               data-url="{{ url($page->slug ?? '') }}">
                                        <label class="form-check-label small" for="page_{{ $page->id }}">
                                            {{ $page->title }}
                                        </label>
                                    </div>
                                @empty
                                    <p class="text-muted small mb-0">No pages available</p>
                                @endforelse
                            </div>
                        </div>
                        <div class="card-footer p-2">
                            <button type="button" id="btn-add-pages" class="btn btn-sm btn-secondary w-100">
                                Add Selected to Menu
                            </button>
                        </div>
                    </div>

                    {{-- Categories (Optional) --}}
                    @if(isset($categories) && count($categories) > 0)
                    <div class="card mb-3">
                        <div class="card-header d-flex justify-content-between align-items-center">
                            <strong>Categories</strong>
                            <span class="badge bg-secondary" id="category-count">0 selected</span>
                        </div>
                        <div class="card-body p-2" style="max-height:300px; overflow-y:auto;">
                            @foreach($categories as $category)
                                <div class="form-check mb-1">
                                    <input class="form-check-input category-checkbox" type="checkbox" 
                                           value="{{ $category->id }}"
                                           id="cat_{{ $category->id }}"
                                           data-label="{{ $category->name }}"
                                           data-url="{{ url('category/' . $category->slug) }}">
                                    <label class="form-check-label small" for="cat_{{ $category->id }}">
                                        {{ $category->name }}
                                    </label>
                                </div>
                            @endforeach
                        </div>
                        <div class="card-footer p-2">
                            <button type="button" id="btn-add-categories" class="btn btn-sm btn-secondary w-100">
                                Add Selected to Menu
                            </button>
                        </div>
                    </div>
                    @endif
                </div>

                {{-- RIGHT SIDE: Menu Structure --}}
                <div class="col-md-8">
                    <div class="card">
                        <div class="card-header d-flex justify-content-between align-items-center">
                            <strong>Menu Structure</strong>
                            <button type="submit" class="btn btn-success btn-sm">
                                <i class="bi bi-save"></i> Save Menu
                            </button>
                        </div>
                        <div class="card-body">
                            <div id="menu-empty-state" class="text-center py-5 text-muted" style="display: none;">
                                <i class="bi bi-list-ul" style="font-size: 3rem;"></i>
                                <p class="mt-2">Add menu items from the left panels</p>
                            </div>
                            
                            <div id="menu-container">
                                <ol id="menu-items" class="menu-builder">
                                    @if($menu->exists && $menu->items)
                                        @foreach($menu->items->where('parent_id', null)->sortBy('sort_order') as $item)
                                            @include('website_menus.partials.menu-item', ['item' => $item])
                                        @endforeach
                                    @endif
                                </ol>
                                <p class="text-muted small mt-2 mb-0">
                                    <i class="bi bi-info-circle"></i> Drag items to reorder. Drag right to create sub-items (nested menus).
                                </p>
                            </div>
                        </div>
                        <div class="card-footer text-end">
                            <button type="button" class="btn btn-outline-secondary" onclick="window.history.back()">
                                Cancel
                            </button>
                            <button type="submit" class="btn btn-success">
                                <i class="bi bi-save"></i> Save Menu
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Hidden JSON data --}}
            <input type="hidden" name="items" id="menu_items_json">
        </form>
    </div>
</div>
@endsection

@push('styles')
<style>
.menu-builder {
    list-style: none;
    padding: 0;
    margin: 0;
}

.menu-builder ol {
    list-style: none;
    padding-left: 30px;
    margin: 0;
}

.menu-item {
    margin-bottom: 8px;
    background: #fff;
    border: 1px solid #dee2e6;
    border-radius: 4px;
}

.menu-item-header {
    padding: 10px 12px;
    background: #f8f9fa;
    border-bottom: 1px solid #dee2e6;
    cursor: move;
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.menu-item-header:hover {
    background: #e9ecef;
}

.menu-item-title {
    font-weight: 500;
    flex-grow: 1;
}

.menu-item-controls {
    display: flex;
    gap: 5px;
}

.menu-item-body {
    padding: 12px;
    display: none;
}

.menu-item-body.show {
    display: block;
}

.menu-item.ui-sortable-helper {
    opacity: 0.8;
}

.menu-item.ui-sortable-placeholder {
    visibility: visible !important;
    background: #e7f3ff;
    border: 2px dashed #0d6efd;
}

.drag-handle {
    cursor: move;
    color: #6c757d;
}

.btn-icon {
    padding: 0.25rem 0.5rem;
    font-size: 0.875rem;
}
</style>
@endpush

@push('scripts') 
<script src="https://code.jquery.com/ui/1.13.2/jquery-ui.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/nestedSortable/2.0.0/jquery.mjs.nestedSortable.js" integrity="sha512-kJSNfXzJbdNIbz50LBNuLnd0yvrtmnbKMhE5dbxcY+53U7PAseFJ/zp7CwQ8JJnvV5HsHOIievSwOxzJ+sO92g==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>

<script>
$(document).ready(function() {
    let itemCounter = 0;

    // Initialize nested sortable with proper configuration
    function initSortable() {
        $('#menu-items').nestedSortable({
            handle: '.menu-item-header',
            items: 'li.menu-item',
            toleranceElement: '> div',
            maxLevels: 5,
            placeholder: 'ui-sortable-placeholder',
            forcePlaceholderSize: true,
            helper: 'clone',
            opacity: 0.6,
            revert: 250,
            tabSize: 30,
            tolerance: 'pointer',
            isTree: true,
            expandOnHover: 700,
            startCollapsed: false,
            listType: 'ol',
            protectRoot: false,
            rootID: 'menu-items',
            stop: function(event, ui) {
                updateEmptyState();
            },
            relocate: function(event, ui) {
                console.log('Menu structure updated');
            }
        });
    }

    // Initialize on page load
    if ($('#menu-items li').length > 0) {
        initSortable();
    }

    // Add custom link
    $('#btn-add-custom').on('click', function() {
        const label = $('#custom_label').val().trim();
        const url = $('#custom_url').val().trim();
        
        if (!label) {
            alert('Please enter a label');
            return;
        }
        
        addMenuItem({
            id: null,
            label: label,
            url: url || '#',
            description: '',
            target: '_self'
        });
        
        $('#custom_label').val('');
        $('#custom_url').val('');
    });

    // Add selected pages
    $('#btn-add-pages').on('click', function() {
        const checked = $('.page-checkbox:checked');
        
        if (checked.length === 0) {
            alert('Please select at least one page');
            return;
        }
        
        checked.each(function() {
            addMenuItem({
                id: null,
                label: $(this).data('label'),
                url: $(this).data('url'),
                description: '',
                target: '_self'
            });
            $(this).prop('checked', false);
        });
        
        updatePageCount();
    });

    // Add selected categories
    $('#btn-add-categories').on('click', function() {
        const checked = $('.category-checkbox:checked');
        
        if (checked.length === 0) {
            alert('Please select at least one category');
            return;
        }
        
        checked.each(function() {
            addMenuItem({
                id: null,
                label: $(this).data('label'),
                url: $(this).data('url'),
                description: '',
                target: '_self'
            });
            $(this).prop('checked', false);
        });
        
        updateCategoryCount();
    });

    // Update counts on checkbox change
    $('.page-checkbox').on('change', updatePageCount);
    $('.category-checkbox').on('change', updateCategoryCount);

    function updatePageCount() {
        const count = $('.page-checkbox:checked').length;
        $('#page-count').text(count + ' selected');
    }

    function updateCategoryCount() {
        const count = $('.category-checkbox:checked').length;
        $('#category-count').text(count + ' selected');
    }

    // Add menu item to DOM
    function addMenuItem(data) {
        itemCounter++;
        const itemId = data.id || 'new_' + itemCounter;
        
        const html = `
            <li class="menu-item" data-id="${itemId}">
                <div class="menu-item-header">
                    <span class="drag-handle me-2">☰</span>
                    <span class="menu-item-title">${escapeHtml(data.label)}</span>
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
                            <input type="text" class="form-control form-control-sm item-label" value="${escapeHtml(data.label)}">
                        </div>
                        <div class="col-md-6 mb-2">
                            <label class="form-label small">URL</label>
                            <input type="text" class="form-control form-control-sm item-url" value="${escapeHtml(data.url)}">
                        </div>
                    </div>
                    <div class="mb-2">
                        <label class="form-label small">Description (optional)</label>
                        <textarea class="form-control form-control-sm item-description" rows="2">${escapeHtml(data.description)}</textarea>
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-2">
                            <label class="form-label small">Target</label>
                            <select class="form-control form-control-sm item-target">
                                <option value="_self" ${data.target === '_self' ? 'selected' : ''}>Same Window (_self)</option>
                                <option value="_blank" ${data.target === '_blank' ? 'selected' : ''}>New Window (_blank)</option>
                            </select>
                        </div>
                        <div class="col-md-6 mb-2">
                            <label class="form-label small">Status</label>
                            <select class="form-control form-control-sm item-status">
                                <option value="active" ${data.status === 'active' ? 'selected' : ''}>Active</option>
                                <option value="inactive" ${data.status === 'inactive' ? 'selected' : ''}>Inactive</option>
                            </select>
                        </div>
                    </div>
                </div>
                <ol></ol>
            </li>
        `;
        
        $('#menu-items').append(html);
        updateEmptyState();
    }

    // Toggle item edit panel
    $(document).on('click', '.toggle-item', function() {
        const $item = $(this).closest('.menu-item');
        const $body = $item.find('> .menu-item-body');
        const $icon = $(this).find('i');
        
        $body.toggleClass('show');
        $icon.toggleClass('bi-chevron-down bi-chevron-up');
    });

    // Delete item
    $(document).on('click', '.delete-item', function() {
        if (confirm('Are you sure you want to delete this menu item?')) {
            $(this).closest('.menu-item').remove();
            updateEmptyState();
        }
    });

    // Update title on label change
    $(document).on('input', '.item-label', function() {
        const title = $(this).val() || '(no title)';
        $(this).closest('.menu-item').find('.menu-item-title').text(title);
    });

    // Update empty state
    function updateEmptyState() {
        if ($('#menu-items > li').length === 0) {
            $('#menu-empty-state').show();
            $('#menu-container').hide();
        } else {
            $('#menu-empty-state').hide();
            $('#menu-container').show();
        }
    }

    // Build JSON tree before submit
    $('#website-menu-form').on('submit', function(e) {
        const tree = buildMenuTree($('#menu-items'));
        $('#menu_items_json').val(JSON.stringify(tree));
    });

    function buildMenuTree($list) {
        const items = [];
        let order = 0;
        
        $list.children('li').each(function() {
            const $item = $(this);
            const $body = $item.find('> .menu-item-body');
            
            const item = {
                id: $item.data('id'),
                label: $body.find('> .row:first .item-label').val(),
                url: $body.find('> .row:first .item-url').val(),
                description: $body.find('.item-description').val(),
                target: $body.find('.item-target').val(),
                status: $body.find('.item-status').val(),
                sort_order: order++,
                children: buildMenuTree($item.find('> ol'))
            };
            
            items.push(item);
        });
        
        return items;
    }

    function escapeHtml(text) {
        if (!text) return '';
        const map = {
            '&': '&amp;',
            '<': '&lt;',
            '>': '&gt;',
            '"': '&quot;',
            "'": '&#039;'
        };
        return text.replace(/[&<>"']/g, m => map[m]);
    }

    // Initialize
    updateEmptyState();
    updatePageCount();
    if ($('.category-checkbox').length) {
        updateCategoryCount();
    }

    // Initialize existing items as collapsed
    $('.menu-item').each(function() {
        $(this).find('> .menu-item-body').removeClass('show');
    });
});
</script>
@endpush