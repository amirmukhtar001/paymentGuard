{{-- resources/views/website_menus/form.blade.php --}}
@extends('layouts.' . config('settings.active_layout'))

@section('content')
<div class="row">
    <div class="col-12">
        {{-- Success/Error Messages --}}
        @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="bi bi-check-circle-fill me-2"></i>{{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
        @endif

        @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="bi bi-exclamation-triangle-fill me-2"></i>{{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
        @endif

        @if($errors->any())
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="bi bi-exclamation-triangle-fill me-2"></i>
            <strong>Please fix the following errors:</strong>
            <ul class="mb-0 mt-2">
                @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
                @endforeach
            </ul>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
        @endif

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

                        <div class="col-md-2">
                            <div class="mb-3">
                                <label class="form-label">Menu Title <span class="text-danger">*</span></label>
                                <input type="text" name="title" class="form-control" value="{{ old('title', $menu->title) }}" required>
                            </div>
                        </div>

                        <div class="col-md-2">
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
                                    @include('website_menus.partials.menu-item-sortable', ['item' => $item])
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

@push('stylesheets')
<style>
    .menu-builder {
        list-style: none;
        padding: 0;
        margin: 0;
        min-height: 50px;
    }

    .menu-builder ol {
        list-style: none;
        padding-left: 40px;
        margin: 0;
        min-height: 20px;
    }

    .menu-item {
        margin-bottom: 2px;
        background: #fff;
        border-radius: 4px;
        transition: all 0.2s ease;
    }

    .menu-item-header {
        padding: 12px 15px;
        background: linear-gradient(to bottom, #7ba4c6 0%, #5b8db8 100%);
        cursor: move;
        display: flex;
        justify-content: space-between;
        align-items: center;
        user-select: none;
        border-radius: 4px;
        color: #1a1a1a;
        font-weight: 500;
        border: 1px solid #6b94b3;
    }

    .menu-item-header:hover {
        background: linear-gradient(to bottom, #8db4d1 0%, #6b9dc3 100%);
    }

    .menu-item-header:active {
        cursor: grabbing;
    }

    .menu-item-title {
        font-weight: 500;
        flex-grow: 1;
        color: #1a1a1a;
    }

    .menu-item-controls {
        display: flex;
        gap: 8px;
        align-items: center;
    }

    .menu-item-body {
        padding: 15px;
        display: none;
        background: #f8f9fa;
        border: 1px solid #dee2e6;
        border-top: none;
        border-radius: 0 0 4px 4px;
    }

    .menu-item-body.show {
        display: block;
        animation: slideDown 0.2s ease;
    }

    @keyframes slideDown {
        from {
            opacity: 0;
            max-height: 0;
        }

        to {
            opacity: 1;
            max-height: 500px;
        }
    }

    .sortable-ghost {
        opacity: 0.4;
        background: #e7f3ff;
    }

    .sortable-drag {
        opacity: 0.9;
        box-shadow: 0 5px 15px rgba(0, 0, 0, 0.3);
    }

    .drag-handle {
        cursor: move;
        color: #2c3e50;
        font-size: 1rem;
        display: flex;
        align-items: center;
        padding: 0 5px;
    }

    .drag-handle:hover {
        color: #1a252f;
    }

    .btn-toggle {
        background: rgba(255, 255, 255, 0.2);
        border: 1px solid rgba(255, 255, 255, 0.3);
        border-radius: 50%;
        width: 28px;
        height: 28px;
        padding: 0;
        display: flex;
        align-items: center;
        justify-content: center;
        color: #2c3e50;
        transition: all 0.2s ease;
    }

    .btn-toggle:hover {
        background: rgba(255, 255, 255, 0.4);
        color: #1a252f;
    }

    .btn-toggle i {
        font-size: 14px;
        transition: transform 0.2s ease;
    }

    .btn-toggle.collapsed i {
        transform: rotate(180deg);
    }

    .btn-delete {
        background: transparent;
        border: none;
        border-radius: 4px;
        width: 28px;
        height: 28px;
        padding: 0;
        display: flex;
        align-items: center;
        justify-content: center;
        color: #2c3e50;
        transition: all 0.2s ease;
    }

    .btn-delete:hover {
        background: rgba(220, 53, 69, 0.8);
        color: white;
    }

    .btn-delete i {
        font-size: 16px;
    }

    .menu-item-body .form-label {
        font-weight: 600;
        margin-bottom: 0.25rem;
        color: #495057;
        font-size: 0.875rem;
    }

    .menu-item-body .form-control {
        border: 1px solid #ced4da;
    }

    .menu-item-body .form-control:focus {
        border-color: #7ba4c6;
        box-shadow: 0 0 0 0.2rem rgba(123, 164, 198, 0.25);
    }

    #menu-empty-state i {
        color: #adb5bd;
    }

    /* Nested items styling */
    .menu-builder>li>.menu-item-header {
        background: linear-gradient(to bottom, #7ba4c6 0%, #5b8db8 100%);
    }

    .menu-builder ol ol>li>.menu-item-header {
        background: linear-gradient(to bottom, #92b3cf 0%, #7ba4c6 100%);
        padding-left: 20px;
    }

    .menu-builder ol ol ol>li>.menu-item-header {
        background: linear-gradient(to bottom, #a8c4db 0%, #92b3cf 100%);
        padding-left: 25px;
    }

    .menu-builder ol ol ol ol>li>.menu-item-header {
        background: linear-gradient(to bottom, #bdd4e7 0%, #a8c4db 100%);
        padding-left: 30px;
    }

    .toggle-item {
        color: #000!important;
    }

    .delete-item {
        color: #f50a0aff!important;
    }
</style>
@endpush

@push('scripts')
<!-- Sortable.js - Much more reliable than nestedSortable -->
<script src="https://cdn.jsdelivr.net/npm/sortablejs@1.15.0/Sortable.min.js"></script>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Check if Sortable is loaded
        if (typeof Sortable === 'undefined') {
            console.error('Sortable.js failed to load. Please check your internet connection.');
            alert('Error: Drag and drop library failed to load. Please refresh the page.');
            return;
        }

        let itemCounter = 0;
        const menuItems = document.getElementById('menu-items');

        if (!menuItems) {
            console.error('Menu items container not found');
            return;
        }

        // Initialize Sortable.js on main list and all nested lists
        function initSortable(element) {
            if (!element) return;

            Sortable.create(element, {
                group: 'nested',
                animation: 150,
                fallbackOnBody: true,
                swapThreshold: 0.65,
                handle: '.drag-handle',
                ghostClass: 'sortable-ghost',
                dragClass: 'sortable-drag',

                onEnd: function(evt) {
                    updateEmptyState();
                    console.log('Item moved');
                }
            });

            // Also initialize sortable on any nested ol elements
            element.querySelectorAll('ol').forEach(function(nestedOl) {
                if (!nestedOl.sortable) {
                    initSortable(nestedOl);
                }
            });
        }

        // Initialize sortable
        initSortable(menuItems);

        // Add custom link
        document.getElementById('btn-add-custom').addEventListener('click', function() {
            const label = document.getElementById('custom_label').value.trim();
            const url = document.getElementById('custom_url').value.trim();

            if (!label) {
                alert('Please enter a label');
                return;
            }

            addMenuItem({
                id: null,
                label: label,
                url: url || '#',
                description: '',
                target: '_self',
                status: 'active'
            });

            document.getElementById('custom_label').value = '';
            document.getElementById('custom_url').value = '';
        });

        // Add selected pages
        document.getElementById('btn-add-pages').addEventListener('click', function() {
            const checked = document.querySelectorAll('.page-checkbox:checked');

            if (checked.length === 0) {
                alert('Please select at least one page');
                return;
            }

            checked.forEach(function(checkbox) {
                addMenuItem({
                    id: null,
                    label: checkbox.dataset.label,
                    url: checkbox.dataset.url,
                    description: '',
                    target: '_self',
                    status: 'active'
                });
                checkbox.checked = false;
            });

            updatePageCount();
        });

        // Update counts on checkbox change
        document.querySelectorAll('.page-checkbox').forEach(function(cb) {
            cb.addEventListener('change', updatePageCount);
        });

        function updatePageCount() {
            const count = document.querySelectorAll('.page-checkbox:checked').length;
            document.getElementById('page-count').textContent = count + ' selected';
        }

        // Add menu item to DOM
        function addMenuItem(data) {
            itemCounter++;
            const itemId = data.id || 'new_' + itemCounter;

            const li = document.createElement('li');
            li.className = 'menu-item';
            li.dataset.id = itemId;

            li.innerHTML = `
            <div class="menu-item-header">
                <div class="d-flex align-items-center">
                    <span class="drag-handle me-2">☰</span>
                    <span class="menu-item-title">${escapeHtml(data.label)}</span>
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
                        <input type="text" class="form-control form-control-sm item-label" value="${escapeHtml(data.label)}" placeholder="Menu item name">
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12 mb-2">
                        <label class="form-label">URL</label>
                        <input type="text" class="form-control form-control-sm item-url" value="${escapeHtml(data.url)}" placeholder="https://example.com">
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12 mb-2">
                        <label class="form-label">Title</label>   
                        <input type="text" class="form-control form-control-sm item-description" value="${escapeHtml(data.description)}" placeholder="Optional description or tooltip text">
                   
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6 mb-2">
                        <label class="form-label">Open Link In</label>
                        <select class="form-control form-control-sm item-target">
                            <option value="_self" ${data.target === '_self' ? 'selected' : ''}>Same Window</option>
                            <option value="_blank" ${data.target === '_blank' ? 'selected' : ''}>New Tab</option>
                        </select>
                    </div>
                    <div class="col-md-6 mb-2">
                        <label class="form-label">Status</label>
                        <select class="form-control form-control-sm item-status">
                            <option value="active" ${data.status === 'active' ? 'selected' : ''}>Active</option>
                            <option value="inactive" ${data.status === 'inactive' ? 'selected' : ''}>Inactive</option>
                        </select>
                    </div>
                </div>
            </div>
            <ol></ol>
        `;

            menuItems.appendChild(li);

            // Initialize sortable on the new nested ol
            const nestedOl = li.querySelector('ol');
            if (nestedOl) {
                initSortable(nestedOl);
            }

            // Small delay to ensure DOM is ready before attaching events
            setTimeout(function() {
                attachItemEvents(li);
                updateEmptyState();
            }, 10);
        }

        // Attach event listeners to menu item
        function attachItemEvents(li) {
            // Safety check - make sure element exists
            if (!li) return;

            // Toggle item edit panel
            const toggleBtn = li.querySelector('.toggle-item');
            if (toggleBtn) {
                toggleBtn.addEventListener('click', function(e) {
                    e.stopPropagation(); // Prevent drag when clicking toggle
                    const body = li.querySelector('.menu-item-body');
                    const btn = this;

                    if (body) {
                        body.classList.toggle('show');
                        btn.classList.toggle('collapsed');
                    }
                });
            }

            // Delete item
            const deleteBtn = li.querySelector('.delete-item');
            if (deleteBtn) {
                deleteBtn.addEventListener('click', function(e) {
                    e.stopPropagation(); // Prevent drag when clicking delete
                    if (confirm('Are you sure you want to delete this menu item?')) {
                        li.remove();
                        updateEmptyState();
                    }
                });
            }

            // Update title on label change
            const labelInput = li.querySelector('.item-label');
            if (labelInput) {
                labelInput.addEventListener('input', function() {
                    const title = this.value || '(no title)';
                    const titleElement = li.querySelector('.menu-item-title');
                    if (titleElement) {
                        titleElement.textContent = title;
                    }
                });
            }
        }

        // Update empty state
        function updateEmptyState() {
            const hasItems = menuItems.children.length > 0;
            document.getElementById('menu-empty-state').style.display = hasItems ? 'none' : 'block';
            document.getElementById('menu-container').style.display = hasItems ? 'block' : 'none';
        }

        // Build JSON tree before submit
        document.getElementById('website-menu-form').addEventListener('submit', function(e) {
            const tree = buildMenuTree(menuItems);
            const jsonString = JSON.stringify(tree, null, 2);

            // Debug: Log the JSON structure
            console.log('Saving menu structure:', tree);
            console.log('JSON string:', jsonString);

            // Set the hidden input value
            document.getElementById('menu_items_json').value = jsonString;

            // Optional: Validate that we have at least one menu item
            if (tree.length === 0) {
                alert('Please add at least one menu item before saving.');
                e.preventDefault();
                return false;
            }
        });

        function buildMenuTree(list) {
            const items = [];
            let order = 0;

            Array.from(list.children).forEach(function(li) {
                if (!li.classList.contains('menu-item')) return;

                // Get the original ID - preserve it if it's a number (existing item)
                let itemId = li.dataset.id;

                // Only include ID if it's an actual database ID (number)
                if (itemId && !itemId.toString().startsWith('new_')) {
                    itemId = parseInt(itemId);
                } else {
                    itemId = null; // New items don't have IDs
                }

                const item = {
                    id: itemId,
                    label: li.querySelector('.item-label').value,
                    url: li.querySelector('.item-url').value,
                    description: li.querySelector('.item-description').value,
                    target: li.querySelector('.item-target').value,
                    status: li.querySelector('.item-status').value,
                    sort_order: order++,
                    children: buildMenuTree(li.querySelector('ol'))
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

        // Initialize existing items
        document.querySelectorAll('.menu-item').forEach(function(item) {
            attachItemEvents(item);
        });

        // Initialize
        updateEmptyState();
        updatePageCount();

        // Make sure all existing items start collapsed
        document.querySelectorAll('.menu-item-body').forEach(function(body) {
            body.classList.remove('show');
        });

        document.querySelectorAll('.toggle-item').forEach(function(btn) {
            btn.classList.add('collapsed');
        });
    });
</script>
@endpush