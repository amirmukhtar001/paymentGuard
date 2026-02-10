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
            <div class="card mb-3 shadow-sm">
                <div class="card-header d-flex align-items-center justify-content-between">
                    <div>
                        <h5 class="mb-0">Website Menu
                            <small class="text-muted">{{ $menu->exists ? 'Edit' : 'Create' }}</small>
                        </h5>
                        <small class="text-muted">Drag &amp; drop to create menu hierarchy (like WordPress).</small>
                    </div>
                    <div>
                        <button type="submit" class="btn btn-success btn-sm">
                            <i class="fas fa-save"></i> Save Menu
                        </button>
                    </div>
                </div>
                <div class="card-body pb-2">
                    <div class="row">
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label class="form-label">Website <span class="text-danger">*</span></label>
                                @php
                                $disable = isset($menu->company_id);
                                @endphp

                                @include('components.company_field', [
                                'companies' => $websites,
                                'select_id' => 'company_id',
                                'selected' => $menu->company_id ?? null,
                                'label' => 'Web Site',
                                'disable' => $disable
                                ])


                                <!-- <select name="company_id" class="form-control" required>
                                    <option value="">Select Website</option>
                                    @foreach($websites as $id => $name)
                                    <option value="{{ $id }}" {{ old('company_id', $menu->company_id) == $id ? 'selected' : '' }}>
                                        {{ $name }}
                                    </option>
                                    @endforeach
                                </select> -->
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
                        <input readonly type="hidden" name="title" class="form-control" value="{{ old('title', $menu->title) }}">

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
                    <div class="card mb-3 shadow-sm menu-side-card">
                        <div class="card-header d-flex justify-content-between align-items-center pointer toggle-panel" data-target="#panel-custom-links">
                            <strong><i class="fas fa-link-45deg me-1"></i> Custom Links</strong>
                            <i class="fas fa-chevron-down panel-arrow"></i>
                        </div>

                        <div id="panel-custom-links" class="collapse show">
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
                    </div>



                    {{-- Sections --}}
                    <div class="card mb-3 shadow-sm menu-side-card">
                        <div class="card-header d-flex justify-content-between align-items-center pointer toggle-panel" data-target="#panel-sections">
                            <strong><i class="fas fa-layer-group me-1"></i> Web Site Home Page Sections</strong>
                            <span class="badge bg-secondary" id="section-count">0 selected</span>
                            <i class="fas fa-chevron-down panel-arrow"></i>
                        </div>

                        <div id="panel-sections" class="collapse">
                            <div class="card-body p-2" style="max-height:300px; overflow-y:auto;">
                                <div id="sections-list">
                                    <p class="text-muted small mb-0" id="sections-placeholder">
                                        Select a website to load sections.
                                    </p>
                                </div>
                            </div>

                            <div class="card-footer p-2">
                                <button type="button" id="btn-add-sections" class="btn btn-sm btn-outline-primary w-100">
                                    Add Selected to Menu
                                </button>
                            </div>
                        </div>
                    </div>



                    {{-- Pages --}}
                    <div class="card mb-3 shadow-sm menu-side-card">
                        <div class="card-header d-flex justify-content-between align-items-center pointer toggle-panel" data-target="#panel-pages">
                            <strong><i class="fas fa-file-text me-1"></i> Pages</strong>
                            <i class="fas fa-chevron-down panel-arrow"></i>
                        </div>

                        <div id="panel-pages" class="collapse">
                            <div class="card-body p-2" style="max-height:300px; overflow-y:auto;">
                                <div id="pages-list">
                                    @forelse($pages as $page)
                                    <div class="form-check mb-1">
                                        <input class="form-check-input page-checkbox"
                                            type="checkbox"
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
                                <button type="button" id="btn-add-pages" class="btn btn-sm btn-outline-primary w-100">
                                    Add Selected to Menu
                                </button>
                            </div>
                        </div>
                    </div>





                </div>

                {{-- RIGHT SIDE: Menu Structure --}}
                <div class="col-md-8">
                    <div class="card shadow-sm" id="menu-builder-card">
                        <div class="card-header d-flex justify-content-between align-items-center">
                            <div>
                                <strong><i class="fas fa-list-ul me-1"></i> Menu Structure</strong>
                                <small class="text-muted d-block">Drag items into the order you prefer. Click the arrow to reveal additional configuration options.</small>
                            </div>
                            <div class="d-flex gap-2">
                                <button type="button" class="btn btn-outline-secondary btn-sm" id="btn-expand-all">
                                    <i class="bx bx-down-arrow"></i>
                                </button>
                                <button type="button" class="btn btn-outline-secondary btn-sm" id="btn-collapse-all">
                                    <i class="bx bx-up-arrow"></i>
                                </button>
                                <button type="submit" class="btn btn-success btn-sm">
                                    <i class="fas fa-save"></i> Save Menu
                                </button>
                            </div>
                        </div>
                        <div class="card-body">
                            <div id="menu-empty-state" class="text-center py-5 text-muted" style="display: none;">
                                <i class="fas fa-list-ul" style="font-size: 3rem;"></i>
                                <p class="mt-2 mb-0">Add menu items from the left panels.</p>
                                <small>They will appear here and you can drag to create hierarchy (submenus).</small>
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
                                    <i class="bx bx-info-circle"></i>
                                    Drag items slightly to the right to make them sub-items, exactly like WordPress.
                                </p>
                            </div>
                        </div>
                        <div class="card-footer text-end">
                            <button type="button" class="btn btn-outline-secondary" onclick="window.history.back()">
                                Cancel
                            </button>
                            <button type="submit" class="btn btn-success">
                                <i class="fas fa-save"></i> Save Menu
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
    /* ---------- GENERAL ---------- */
    .menu-side-card {
        border-radius: 8px;
        overflow: hidden;
        transition: box-shadow 0.2s ease, transform 0.2s ease;
    }

    .menu-side-card:hover {
        transform: translateY(-1px);
        box-shadow: 0 8px 18px rgba(0, 0, 0, 0.08);
    }

    #menu-builder-card {
        border-radius: 8px;
        overflow: hidden;
    }

    /* ---------- MENU BUILDER LIST ---------- */
    .menu-builder {
        list-style: none;
        padding: 0;
        margin: 0;
        min-height: 50px;
    }

    .menu-builder ol {
        list-style: none;
        padding-left: 30px;
        margin: 4px 0 0 0;
        border-left: 1px dashed #d0d7de;
    }

    /* .menu-item {
        margin-bottom: 6px;
        background: #ffffff;
        border-radius: 6px;
        transition: box-shadow 0.2s ease, transform 0.15s ease, background 0.2s ease;
        border: 1px solid #d0e2f3;
        position: relative;
        overflow: hidden;
    }

    .menu-item:hover {
        box-shadow: 0 6px 15px rgba(15, 23, 42, 0.1);
        transform: translateY(-1px);
    }091 560 25 34
 
    .menu-item::before {
        content: '';
        position: absolute;
        left: 0;
        top: 0;
        bottom: 0;
        width: 3px;
        background: linear-gradient(180deg, #3b82f6, #0ea5e9);
        opacity: 0;
        transition: opacity 0.2s ease;
    }

    .menu-item.menu-item-active::before {
        opacity: 1;
    } */

    .menu-item-header {
        padding: 8px 10px;
        background: linear-gradient(to bottom, #eff6ff 0%, #dbeafe 100%);
        cursor: move;
        display: flex;
        justify-content: space-between;
        align-items: center;
        user-select: none;
        color: #0f172a;
        font-weight: 500;
        border-bottom: 1px solid rgba(148, 163, 184, 0.2);
    }

    .menu-item-header-left {
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .menu-item-title {
        font-weight: 600;
        flex-grow: 1;
        color: #0f172a;
        font-size: 0.92rem;
    }

    .menu-item-meta {
        font-size: 0.75rem;
        color: #64748b;
    }

    .menu-item-controls {
        display: flex;
        gap: 6px;
        align-items: center;
    }

    .drag-handle {
        cursor: move;
        font-size: 1rem;
        line-height: 1;
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 6px 8px;
        border-radius: 4px;
        background: rgba(148, 163, 184, 0.2);
        color: #1f2937;
        transition: background 0.2s ease, transform 0.1s ease;
    }

    .drag-handle:hover {
        background: rgba(148, 163, 184, 0.35);
        transform: translateY(-1px);
    }

    .btn-toggle {
        border-radius: 4px;
        width: 28px;
        height: 28px;
        padding: 0;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        color: #0f172a;
        border: none;
        background: rgba(255, 255, 255, 0.7);
        transition: background 0.2s ease, transform 0.15s ease, box-shadow 0.2s ease;
    }

    .btn-toggle:hover {
        background: #ffffff;
        box-shadow: 0 0 0 1px rgba(15, 23, 42, 0.08);
    }

    .btn-toggle i {
        font-size: 15px;
        transition: transform 0.2s ease;
    }

    .btn-toggle.collapsed i {
        transform: rotate(0deg);
    }

    .btn-toggle:not(.collapsed) i {
        transform: rotate(180deg);
    }

    .btn-delete {
        background: transparent;
        border: none;
        border-radius: 4px;
        width: 28px;
        height: 28px;
        padding: 0;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        transition: background 0.2s ease, color 0.2s ease, transform 0.1s ease;
        color: #dc2626;
    }

    .btn-delete:hover {
        background: rgba(248, 113, 113, 0.12);
        transform: translateY(-1px);
    }

    .btn-delete i {
        font-size: 16px;
    }

    /* ---------- COLLAPSIBLE BODY WITH ANIMATION ---------- */
    .menu-item-body {
        background: #f9fafb;
        border-top: 1px solid #e5e7eb;
        padding: 0 12px;
        overflow: hidden;
        max-height: 0;
        opacity: 0;
        transition: max-height 0.25s ease, opacity 0.25s ease, padding 0.2s ease;
    }

    .menu-item-body-inner {
        padding-top: 10px;
        padding-bottom: 10px;
    }

    .menu-item-body.show {
        max-height: 500px;
        /* big enough to hold content */
        opacity: 1;
        padding-top: 4px;
        padding-bottom: 10px;
    }

    .menu-item-body .form-label {
        font-weight: 600;
        margin-bottom: 0.25rem;
        color: #374151;
        font-size: 0.83rem;
    }

    .menu-item-body .form-control,
    .menu-item-body .form-select {
        border-radius: 4px;
        font-size: 0.85rem;
    }

    .menu-item-body .form-control:focus,
    .menu-item-body .form-select:focus {
        border-color: #3b82f6;
        box-shadow: 0 0 0 0.12rem rgba(59, 130, 246, 0.25);
    }

    /* ---------- SORTABLE STATES ---------- */
    .sortable-ghost {
        opacity: 0.6;
        background: #eff6ff;
        border-style: dashed;
    }

    .sortable-drag {
        opacity: 0.95;
        box-shadow: 0 10px 30px rgba(15, 23, 42, 0.35);
        transform: rotate(-1deg);
    }

    .sortable-chosen {
        background: #e0f2fe;
    }

    #menu-empty-state i {
        color: #cbd5f5;
    }

    /* Toggle color helpers */
    .toggle-item {
        color: #0f172a !important;
    }

    .delete-item {
        color: #dc2626 !important;
    }

    .pointer {
        cursor: pointer;
    }

    .panel-arrow {
        transition: transform 0.25s ease;
    }

    .collapse:not(.show) {
        display: none;
    }
</style>

@endpush

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/sortablejs@1.15.0/Sortable.min.js"></script>



<script>
    $(document).ready(function() {

        document.querySelectorAll('.toggle-panel').forEach(function(header) {
            header.addEventListener('click', function() {
                const target = document.querySelector(this.dataset.target);

                if (!target) return;

                const arrow = this.querySelector('.panel-arrow');

                if (target.classList.contains('show')) {
                    target.classList.remove('show');
                    arrow.style.transform = "rotate(0deg)";
                } else {
                    target.classList.add('show');
                    arrow.style.transform = "rotate(180deg)";
                }
            });
        });


        function updateTitle() {
            let company = $('select[name="company_id"] option:selected').text().trim();
            let menuType = $('select[name="menu_type_id"] option:selected').text().trim();

            // Ignore default "Select ..." values
            if (company && company !== "Select Website" &&
                menuType && menuType !== "Select Menu Type") {
                $('input[name="title"]').val(company + ' - ' + menuType);
            }
        }

        // Trigger on dropdown change
        $('select[name="company_id"], select[name="menu_type_id"]').on('change', updateTitle);

        // Make the Title field read-only
        $('input[name="title"]').prop('readonly', true);

        // Populate on page load if values already selected
        updateTitle();
    });
</script>


<script>
    document.addEventListener('DOMContentLoaded', function() {
        if (typeof Sortable === 'undefined') {
            console.error('Sortable.js failed to load.');
            alert('Error: Drag and drop library failed to load. Please refresh the page.');
            return;
        }

        let itemCounter = 0;
        const menuItems = document.getElementById('menu-items');

        if (!menuItems) {
            console.error('Menu items container not found');
            return;
        }

        /* ------------------------- SORTABLE INIT ------------------------- */
        function initSortable(element) {
            if (!element || element.dataset.sortableInit === '1') return;

            Sortable.create(element, {
                group: 'nested',
                animation: 150,
                fallbackOnBody: true,
                swapThreshold: 0.4,
                handle: '.drag-handle',
                ghostClass: 'sortable-ghost',
                dragClass: 'sortable-drag',
                chosenClass: 'sortable-chosen',
                onStart: function(evt) {
                    evt.item.classList.add('menu-item-active');
                },
                onEnd: function(evt) {
                    evt.item.classList.remove('menu-item-active');
                    updateEmptyState();
                    refreshDepthClasses();
                }
            });

            element.dataset.sortableInit = '1';

            element.querySelectorAll('ol').forEach(function(nestedOl) {
                initSortable(nestedOl);
            });
        }

        initSortable(menuItems);

        /* ---------------------- LEFT PANEL ACTIONS ---------------------- */

        // Add custom link
        document.getElementById('btn-add-custom').addEventListener('click', function() {
            const labelInput = document.getElementById('custom_label');
            const urlInput = document.getElementById('custom_url');
            const label = labelInput.value.trim();
            const url = urlInput.value.trim();

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

            labelInput.value = '';
            urlInput.value = '';
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

        document.querySelectorAll('.page-checkbox').forEach(function(cb) {
            cb.addEventListener('change', updatePageCount);
        });

        function updatePageCount() {
            const count = document.querySelectorAll('.page-checkbox:checked').length;
            document.getElementById('page-count').textContent = count + ' selected';
        }

        /* ------------------------- ADD MENU ITEM ------------------------- */
        function addMenuItem(data) {
            itemCounter++;
            const itemId = data.id || 'new_' + itemCounter;

            const li = document.createElement('li');
            li.className = 'menu-item';
            li.dataset.id = itemId;

            li.innerHTML = `
                <div class="menu-item-header">
                    <div class="menu-item-header-left">
                        <span class="drag-handle" title="Drag to move">☰</span>
                        <div>
                            <span class="menu-item-title">${escapeHtml(data.label)}</span>
                            <div class="menu-item-meta">Custom Link</div>
                        </div>
                    </div>
                    <div class="menu-item-controls">
                        <button type="button" class="btn btn-toggle collapsed toggle-item" title="Show more options">
                            <i class="fas fa-chevron-down"></i>
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
                                <label class="form-label">Title / Tooltip</label>
                                  <input type="text" class="form-control form-control-sm item-description" value="${escapeHtml(data.description)}" placeholder="Optional description or tooltip text"> 
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6 mb-2">
                                <label class="form-label">Open Link In</label>
                                <select class="form-select form-select-sm item-target">
                                    <option value="_self" ${data.target === '_self' ? 'selected' : ''}>Same Window</option>
                                    <option value="_blank" ${data.target === '_blank' ? 'selected' : ''}>New Tab</option>
                                </select>
                            </div>
                            <div class="col-md-6 mb-2">
                                <label class="form-label">Status</label>
                                <select class="form-select form-select-sm item-status">
                                    <option value="active" ${data.status === 'active' ? 'selected' : ''}>Active</option>
                                    <option value="inactive" ${data.status === 'inactive' ? 'selected' : ''}>Inactive</option>
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
                <ol></ol>
            `;

            menuItems.appendChild(li);

            const nestedOl = li.querySelector('ol');
            if (nestedOl) {
                initSortable(nestedOl);
            }

            setTimeout(function() {
                attachItemEvents(li);
                updateEmptyState();
                refreshDepthClasses();
            }, 10);
        }



        /* ---------------------- SECTIONS ---------------------- */

        /* ---------------------- SECTIONS LOADING (AJAX) ---------------------- */

        const sectionsEndpoint = "{{ route('settings.website-sections.ajax.website-sections.index') }}";

        function updateSectionCount() {
            const count = document.querySelectorAll('.section-checkbox:checked').length;
            const badge = document.getElementById('section-count');
            if (badge) {
                badge.textContent = count + ' selected';
            }
        }

        // Delegated listener so it also works for dynamically added checkboxes
        document.addEventListener('change', function(e) {
            if (e.target.classList.contains('section-checkbox')) {
                updateSectionCount();
            }
        });

        function renderSections(sections) {
            const container = document.getElementById('sections-list');
            if (!container) return;

            if (!sections.length) {
                container.innerHTML = '<p class="text-muted small mb-0">No sections available</p>';
                updateSectionCount();
                return;
            }

            let html = '';
            sections.forEach(function(section) {
                html += `
                <div class="form-check mb-1">
                    <input
                        class="form-check-input section-checkbox"
                        type="checkbox"
                        value="${section.id}"
                        id="section_${section.id}"
                        data-label="${escapeHtml(section.title+' '+section.subheading)}"
                        data-url="#${section.slug}">
                    <label class="form-check-label small" for="section_${section.id}">
                        ${escapeHtml(section.title+' '+section.subheading)}
                    </label>
                </div>
            `;
            });

            container.innerHTML = html;
            updateSectionCount();
        }

        function loadSectionsForCompany(companyId) {
            const container = document.getElementById('sections-list');
            if (!container) return;
            if (!companyId) {
                container.innerHTML = '<p class="text-muted small mb-0">Select a website to load sections.</p>';
                updateSectionCount();
                return;
            }
            container.innerHTML = '<p class="text-muted small mb-0">Loading sections...</p>';
            updateSectionCount();
            $.getJSON(sectionsEndpoint, {
                company_id: companyId
            }).done(function(data) {
                renderSections(data || []);
            }).fail(function() {
                container.innerHTML = '<p class="text-danger small mb-0">Failed to load sections.</p>';
            });
        }

        // When company dropdown changes ➜ reload sections
        const companySelect = document.querySelector('select[name="company_id"]');
    
        if (companySelect) {
            companySelect.addEventListener('change', function() {
                loadSectionsForCompany(this.value);
            });

            // Initial load (for edit form, or when default is pre-selected)
            if (companySelect.value) {
                loadSectionsForCompany(companySelect.value);
            }
        }

        /* ---------------------- ADD SECTIONS TO MENU ---------------------- */

        const btnAddSections = document.getElementById('btn-add-sections');
        if (btnAddSections) {
            btnAddSections.addEventListener('click', function() {
                const checked = document.querySelectorAll('.section-checkbox:checked');

                if (checked.length === 0) {
                    alert('Please select at least one section');
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

                updateSectionCount();
            });
        }
        /* ---------------------- ATTACH ITEM EVENTS ---------------------- */
        function attachItemEvents(li) {
            if (!li) return;

            const header = li.querySelector('.menu-item-header');
            const toggleBtn = li.querySelector('.toggle-item');
            const deleteBtn = li.querySelector('.delete-item');
            const removeLink = li.querySelector('.item-remove-link');
            const labelInput = li.querySelector('.item-label');
            const body = li.querySelector('.menu-item-body');

            if (toggleBtn) {
                toggleBtn.addEventListener('click', function(e) {
                    e.stopPropagation();
                    toggleBody(body, toggleBtn);
                });
            }

            if (header && toggleBtn && body) {
                header.addEventListener('dblclick', function(e) {
                    e.preventDefault();
                    toggleBody(body, toggleBtn);
                });
            }

            if (deleteBtn) {
                deleteBtn.addEventListener('click', function(e) {
                    e.stopPropagation();
                    if (confirm('Are you sure you want to delete this menu item and all its sub-items?')) {
                        li.remove();
                        updateEmptyState();
                        refreshDepthClasses();
                    }
                });
            }

            if (removeLink) {
                removeLink.addEventListener('click', function(e) {
                    e.stopPropagation();
                    if (confirm('Remove this menu item?')) {
                        li.remove();
                        updateEmptyState();
                        refreshDepthClasses();
                    }
                });
            }

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

        function toggleBody(body, toggleBtn) {
            if (!body) return;
            const isOpen = body.classList.contains('show');
            if (isOpen) {
                body.classList.remove('show');
                toggleBtn.classList.add('collapsed');
            } else {
                body.classList.add('show');
                toggleBtn.classList.remove('collapsed');
            }
        }

        /* --------------------- EMPTY STATE & DEPTH ---------------------- */
        function updateEmptyState() {
            const hasItems = menuItems.children.length > 0;
            document.getElementById('menu-empty-state').style.display = hasItems ? 'none' : 'block';
            document.getElementById('menu-container').style.display = hasItems ? 'block' : 'none';
        }

        function refreshDepthClasses() {
            const walk = (list, depth) => {
                Array.from(list.children).forEach(li => {
                    if (!li.classList.contains('menu-item')) return;
                    li.dataset.depth = depth;
                    // color/visual tweaks can be added here based on depth if needed
                    const nested = li.querySelector(':scope > ol');
                    if (nested) {
                        walk(nested, depth + 1);
                    }
                });
            };
            walk(menuItems, 0);
        }

        /* ---------------------- FORM SUBMISSION ------------------------- */
        document.getElementById('website-menu-form').addEventListener('submit', function(e) {
            const tree = buildMenuTree(menuItems);
            const jsonString = JSON.stringify(tree, null, 2);

            console.log('Saving menu structure:', tree);
            document.getElementById('menu_items_json').value = jsonString;

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

                let itemId = li.dataset.id;
                if (itemId && !itemId.toString().startsWith('new_')) {
                    itemId = parseInt(itemId);
                } else {
                    itemId = null;
                }

                const item = {
                    id: itemId,
                    label: li.querySelector('.item-label').value,
                    url: li.querySelector('.item-url').value,
                    description: li.querySelector('.item-description') ? li.querySelector('.item-description').value : '',
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

        /* -------------------- EXPAND/COLLAPSE ALL ---------------------- */
        const btnExpandAll = document.getElementById('btn-expand-all');
        const btnCollapseAll = document.getElementById('btn-collapse-all');

        if (btnExpandAll) {
            btnExpandAll.addEventListener('click', function() {
                document.querySelectorAll('.menu-item-body').forEach(body => body.classList.add('show'));
                document.querySelectorAll('.toggle-item').forEach(btn => btn.classList.remove('collapsed'));
            });
        }

        if (btnCollapseAll) {
            btnCollapseAll.addEventListener('click', function() {
                document.querySelectorAll('.menu-item-body').forEach(body => body.classList.remove('show'));
                document.querySelectorAll('.toggle-item').forEach(btn => btn.classList.add('collapsed'));
            });
        }

        /* -------------------- INITIALIZE EXISTING ----------------------- */
        document.querySelectorAll('.menu-item').forEach(function(item) {
            attachItemEvents(item);
        });

        // Start all existing items collapsed
        document.querySelectorAll('.menu-item-body').forEach(function(body) {
            body.classList.remove('show');
        });

        document.querySelectorAll('.toggle-item').forEach(function(btn) {
            btn.classList.add('collapsed');
        });

        updateEmptyState();
        updatePageCount();
        refreshDepthClasses();
    });
</script>
@endpush