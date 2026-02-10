@extends('layouts.' . config('settings.active_layout'))

@push('styles')
    <style>
        .page-header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            padding: 30px;
            border-radius: 12px;
            color: white;
            margin-bottom: 30px;
            box-shadow: 0 4px 20px rgba(102, 126, 234, 0.4);
        }

        .page-header h4 {
            color: white;
            margin-bottom: 10px;
            font-weight: 700;
        }

        .section-info {
            display: flex;
            gap: 20px;
            flex-wrap: wrap;
        }

        .info-badge {
            background: rgba(255, 255, 255, 0.2);
            backdrop-filter: blur(10px);
            padding: 8px 16px;
            border-radius: 20px;
            font-size: 14px;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .manage-grid {
            display: grid;
            grid-template-columns: 400px 1fr;
            gap: 24px;
            margin-top: 24px;
        }

        @media (max-width: 1200px) {
            .manage-grid {
                grid-template-columns: 1fr;
            }
        }

        .panel {
            background: #fff;
            border-radius: 12px;
            box-shadow: 0 2px 12px rgba(0, 0, 0, 0.08);
            overflow: hidden;
        }

        .panel-header {
            background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
            padding: 20px 24px;
            border-bottom: 1px solid #e0e0e0;
        }

        .panel-title {
            font-size: 18px;
            font-weight: 700;
            color: #2c3e50;
            display: flex;
            align-items: center;
            gap: 12px;
            margin: 0;
        }

        .panel-title i {
            font-size: 24px;
            color: #667eea;
        }

        .panel-body {
            padding: 24px;
        }

        .module-tabs {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 12px;
            margin-bottom: 20px;
        }

        .module-tab {
            padding: 16px;
            border: 2px solid #e8e8e8;
            border-radius: 10px;
            background: #fff;
            cursor: pointer;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            text-align: center;
            font-weight: 600;
            color: #555;
            position: relative;
            overflow: hidden;
        }

        .module-tab::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(102, 126, 234, 0.1), transparent);
            transition: left 0.5s;
        }

        .module-tab:hover::before {
            left: 100%;
        }

        .module-tab:hover {
            border-color: #667eea;
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(102, 126, 234, 0.2);
        }

        .module-tab.active {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border-color: #667eea;
            color: white;
            box-shadow: 0 6px 20px rgba(102, 126, 234, 0.4);
        }

        .module-tab-icon {
            font-size: 28px;
            margin-bottom: 8px;
            display: block;
        }

        .search-box {
            position: relative;
            margin-bottom: 20px;
        }

        .search-box input {
            width: 100%;
            padding: 14px 45px 14px 20px;
            border: 2px solid #e8e8e8;
            border-radius: 10px;
            font-size: 14px;
            transition: all 0.3s;
        }

        .search-box input:focus {
            border-color: #667eea;
            box-shadow: 0 0 0 4px rgba(102, 126, 234, 0.1);
            outline: none;
        }

        .search-box i {
            position: absolute;
            right: 18px;
            top: 50%;
            transform: translateY(-50%);
            color: #999;
            font-size: 18px;
        }

        .items-container {
            max-height: 600px;
            overflow-y: auto;
            padding-right: 8px;
        }

        .items-container::-webkit-scrollbar {
            width: 6px;
        }

        .items-container::-webkit-scrollbar-track {
            background: #f1f1f1;
            border-radius: 10px;
        }

        .items-container::-webkit-scrollbar-thumb {
            background: #667eea;
            border-radius: 10px;
        }

        .available-item {
            padding: 16px;
            border: 2px solid #e8e8e8;
            border-radius: 10px;
            margin-bottom: 12px;
            display: flex;
            align-items: center;
            gap: 16px;
            background: #fff;
            transition: all 0.3s;
        }

        .available-item:hover {
            border-color: #667eea;
            box-shadow: 0 4px 16px rgba(102, 126, 234, 0.15);
            transform: translateX(4px);
        }

        .available-item.added {
            background: linear-gradient(135deg, #d4edda 0%, #c3e6cb 100%);
            border-color: #28a745;
            opacity: 0.7;
        }

        .item-thumbnail {
            width: 60px;
            height: 60px;
            border-radius: 8px;
            object-fit: cover;
            flex-shrink: 0;
            background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 24px;
        }

        .item-thumbnail img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            border-radius: 8px;
        }

        .item-content {
            flex: 1;
            min-width: 0;
        }

        .item-title {
            font-weight: 600;
            color: #2c3e50;
            margin-bottom: 4px;
            font-size: 15px;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .item-meta {
            font-size: 13px;
            color: #7f8c8d;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .module-badge {
            display: inline-flex;
            align-items: center;
            gap: 4px;
            background: #e8e8e8;
            padding: 2px 8px;
            border-radius: 4px;
            font-size: 11px;
            font-weight: 600;
        }

        .current-item {
            background: #fff;
            border: 2px solid #e8e8e8;
            border-radius: 12px;
            margin-bottom: 12px;
            padding: 16px;
            cursor: move;
            transition: all 0.3s;
        }

        .current-item:hover {
            border-color: #667eea;
            box-shadow: 0 4px 16px rgba(102, 126, 234, 0.15);
        }

        .current-item.dragging {
            opacity: 0.5;
            border-style: dashed;
            transform: rotate(2deg);
        }

        .item-row {
            display: flex;
            align-items: center;
            gap: 16px;
        }

        .drag-handle {
            cursor: move;
            color: #bbb;
            font-size: 24px;
            transition: color 0.3s;
        }

        .drag-handle:hover {
            color: #667eea;
        }

        .item-number {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            width: 36px;
            height: 36px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 700;
            font-size: 14px;
            flex-shrink: 0;
            box-shadow: 0 4px 12px rgba(102, 126, 234, 0.3);
        }

        .item-image {
            width: 70px;
            height: 70px;
            border-radius: 10px;
            object-fit: cover;
            flex-shrink: 0;
            background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 28px;
        }

        .item-image img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            border-radius: 10px;
        }

        .item-details {
            flex: 1;
            min-width: 0;
        }

        .item-details-title {
            font-weight: 700;
            color: #2c3e50;
            margin-bottom: 6px;
            font-size: 16px;
        }

        .item-details-meta {
            display: flex;
            gap: 12px;
            font-size: 13px;
            color: #7f8c8d;
        }

        .item-actions {
            display: flex;
            gap: 8px;
            flex-shrink: 0;
        }

        .btn-icon {
            width: 36px;
            height: 36px;
            padding: 0;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 8px;
            border: none;
            cursor: pointer;
            transition: all 0.3s;
            font-size: 18px;
        }

        .btn-star {
            background: #f8f9fa;
            color: #ffc107;
        }

        .btn-star:hover {
            background: #ffc107;
            color: white;
            transform: scale(1.1);
        }

        .btn-star.featured {
            background: #ffc107;
            color: white;
        }

        .btn-remove {
            background: #fff5f5;
            color: #dc3545;
        }

        .btn-remove:hover {
            background: #dc3545;
            color: white;
            transform: scale(1.1);
        }

        .btn-add {
            padding: 8px 16px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            border: none;
            border-radius: 8px;
            font-weight: 600;
            font-size: 13px;
            cursor: pointer;
            transition: all 0.3s;
            display: flex;
            align-items: center;
            gap: 6px;
        }

        .btn-add:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(102, 126, 234, 0.4);
        }

        .featured-badge {
            background: linear-gradient(135deg, #ffc107 0%, #ff9800 100%);
            color: white;
            padding: 4px 10px;
            border-radius: 6px;
            font-size: 11px;
            font-weight: 700;
            display: inline-flex;
            align-items: center;
            gap: 4px;
        }

        .empty-state {
            text-align: center;
            padding: 60px 20px;
            color: #95a5a6;
        }

        .empty-state-icon {
            font-size: 64px;
            margin-bottom: 20px;
            opacity: 0.3;
        }

        .empty-state-title {
            font-size: 18px;
            font-weight: 600;
            color: #7f8c8d;
            margin-bottom: 8px;
        }

        .empty-state-text {
            font-size: 14px;
        }

        .info-card {
            background: linear-gradient(135deg, #e3f2fd 0%, #bbdefb 100%);
            border-left: 4px solid #2196F3;
            padding: 16px;
            border-radius: 8px;
            margin-bottom: 20px;
            font-size: 14px;
            color: #1565c0;
        }

        .info-card i {
            color: #2196F3;
            margin-right: 8px;
            font-size: 18px;
        }

        .badge-success {
            background: linear-gradient(135deg, #28a745 0%, #20c997 100%);
            color: white;
            padding: 6px 12px;
            border-radius: 6px;
            font-size: 12px;
            font-weight: 600;
            display: inline-flex;
            align-items: center;
            gap: 6px;
        }

        .loading-spinner {
            display: flex;
            justify-content: center;
            align-items: center;
            padding: 40px;
        }

        .spinner {
            width: 40px;
            height: 40px;
            border: 4px solid #f3f3f3;
            border-top: 4px solid #667eea;
            border-radius: 50%;
            animation: spin 1s linear infinite;
        }

        @keyframes spin {
            0% {
                transform: rotate(0deg);
            }

            100% {
                transform: rotate(360deg);
            }
        }

        .action-buttons {
            display: flex;
            gap: 12px;
        }
    </style>
@endpush

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/sortablejs@1.15.0/Sortable.min.js"></script>
    <script>
        $(document).ready(function () {
            let currentModule = 'news';
            let searchTimeout = null;
            const sectionId = {{ $section->id }};
            const sectionUuid = '{{ $section->uuid }}';

            // Initialize Sortable
            let sortable = new Sortable(document.getElementById('current-items-list'), {
                animation: 200,
                handle: '.drag-handle',
                ghostClass: 'dragging',
                onEnd: function (evt) {
                    updateItemsOrder();
                }
            });

            // Render current items
            function renderCurrentItems() {
                $('#current-items-list').html('<div class="loading-spinner"><div class="spinner"></div></div>');

                $.ajax({
                    url: '{{ route("sections.manage-items", $section->uuid) }}',
                    method: 'GET',
                    success: function () {
                        let items = @json($section->items);
                        let html = '';

                        if (items.length === 0) {
                            html = `
                                        <div class="empty-state">
                                            <div class="empty-state-icon">📦</div>
                                            <div class="empty-state-title">No items added yet</div>
                                            <div class="empty-state-text">Select a module and add items from the left panel</div>
                                        </div>
                                    `;
                        } else {
                            items.forEach((item, index) => {
                                let moduleItem = item.module_item;
                                let title = moduleItem?.title || moduleItem?.name || 'Untitled';
                                let image = moduleItem?.image || moduleItem?.thumbnail || moduleItem?.photo || moduleItem?.featured_image || null;
                                let imageHtml = image
                                    ? `<img src="${image}" alt="${title}" onerror="this.parentElement.innerHTML='${getModuleIcon(item.module_type)}'">`
                                    : getModuleIcon(item.module_type);
                                let featuredBadge = item.is_featured ? '<span class="featured-badge">⭐ Featured</span>' : '';

                                html += `
                                            <div class="current-item" data-id="${item.id}">
                                                <div class="item-row">
                                                    <span class="drag-handle">
                                                        <i class="bx bx-menu"></i>
                                                    </span>
                                                    <div class="item-number">${index + 1}</div>
                                                    <div class="item-image">${imageHtml}</div>
                                                    <div class="item-details">
                                                        <div class="item-details-title">${title}</div>
                                                        <div class="item-details-meta">
                                                            <span class="module-badge">${getModuleIcon(item.module_type)} ${capitalizeFirst(item.module_type)}</span>
                                                            ${featuredBadge}
                                                        </div>
                                                    </div>
                                                    <div class="item-actions">
                                                        <button class="btn-icon btn-star ${item.is_featured ? 'featured' : ''}" 
                                                                data-id="${item.id}"
                                                                title="Toggle Featured">
                                                            <i class="bx ${item.is_featured ? 'bxs-star' : 'bx-star'}"></i>
                                                        </button>
                                                        <button class="btn-icon btn-remove" 
                                                                data-id="${item.id}"
                                                                title="Remove">
                                                            <i class="bx bx-trash"></i>
                                                        </button>
                                                    </div>
                                                </div>
                                            </div>
                                        `;
                            });
                        }

                        $('#current-items-list').html(html);
                        updateItemCount(items.length);
                        attachCurrentItemHandlers();
                    }
                });
            }

            // Load available items
            function loadAvailableItems(search = '') {
                $('#available-items-list').html('<div class="loading-spinner"><div class="spinner"></div></div>');

                $.ajax({
                    url: '{{ route("sections.get-module-items") }}',
                    method: 'GET',
                    data: {
                        module_type: currentModule,
                        search: search
                    },
                    success: function (items) {
                        let html = '';

                        if (items.length === 0) {
                            html = `
                                        <div class="empty-state">
                                            <div class="empty-state-icon">🔍</div>
                                            <div class="empty-state-title">No items found</div>
                                            <div class="empty-state-text">Try a different search term</div>
                                        </div>
                                    `;
                        } else {
                            // Get current item IDs
                            let currentItemIds = [];
                            let currentItems = @json($section->items);
                            currentItems.forEach(item => {
                                if (item.module_type === currentModule) {
                                    currentItemIds.push(item.module_item_id);
                                }
                            });

                            items.forEach(item => {
                                let title = item.title || 'Untitled';
                                let isAdded = currentItemIds.includes(item.id);
                                let addedClass = isAdded ? 'added' : '';
                                let meta = item.meta || capitalizeFirst(currentModule);

                                let buttonHtml = isAdded
                                    ? '<span class="badge-success"><i class="bx bx-check"></i> Added</span>'
                                    : '<button class="btn-add add-item-btn" data-id="' + item.id + '"><i class="bx bx-plus"></i> Add</button>';

                                html += `
                                            <div class="available-item ${addedClass}">
                                                <div class="item-thumbnail">${getModuleIcon(currentModule)}</div>
                                                <div class="item-content">
                                                    <div class="item-title">${title}</div>
                                                    <div class="item-meta">
                                                        <span class="module-badge">${getModuleIcon(currentModule)} ${capitalizeFirst(currentModule)}</span>
                                                        ${meta !== capitalizeFirst(currentModule) ? '<span>' + meta + '</span>' : ''}
                                                    </div>
                                                </div>
                                                ${buttonHtml}
                                            </div>
                                        `;
                            });
                        }

                        $('#available-items-list').html(html);
                        attachAvailableItemHandlers();
                    },
                    error: function () {
                        $('#available-items-list').html(`
                                    <div class="empty-state">
                                        <div class="empty-state-icon">❌</div>
                                        <div class="empty-state-title">Failed to load items</div>
                                        <div class="empty-state-text">Please try again</div>
                                    </div>
                                `);
                    }
                });
            }

            // Helper functions
            function getModuleIcon(moduleType) {
                const icons = {
                    'news': '📰',
                    'pages': '📄',
                    'galleries': '📸',
                    'leaders': '👥',
                    'departments': '🏢'
                };
                return icons[moduleType] || '📦';
            }

            function capitalizeFirst(str) {
                return str.charAt(0).toUpperCase() + str.slice(1);
            }

            function updateItemCount(count) {
                $('.panel-title .badge').remove();
                $('.panel-title').eq(1).append(` <span class="badge bg-primary">${count}</span>`);
            }

            // Module tab click
            $('.module-tab').on('click', function () {
                $('.module-tab').removeClass('active');
                $(this).addClass('active');
                currentModule = $(this).data('module');
                $('#search-items').val('');
                loadAvailableItems();
            });

            // Search
            $('#search-items').on('input', function () {
                clearTimeout(searchTimeout);
                let search = $(this).val();
                searchTimeout = setTimeout(function () {
                    loadAvailableItems(search);
                }, 300);
            });

            // Attach handlers
            function attachAvailableItemHandlers() {
                $('.add-item-btn').off('click').on('click', function () {
                    addItemToSection($(this).data('id'));
                });
            }

            function attachCurrentItemHandlers() {
                $('.btn-remove').off('click').on('click', function () {
                    removeItemFromSection($(this).data('id'));
                });

                $('.btn-star').off('click').on('click', function () {
                    toggleFeatured($(this).data('id'), $(this));
                });
            }

            // Add item
            function addItemToSection(itemId) {
                $.ajax({
                    url: `/settings/sections/${sectionUuid}/add-item`,
                    method: 'POST',
                    data: {
                        _token: '{{ csrf_token() }}',
                        module_type: currentModule,
                        module_item_id: itemId
                    },
                    success: function (response) {
                        toastr.success(response.message);
                        location.reload(); // Reload to update the items
                    },
                    error: function (xhr) {
                        toastr.error(xhr.responseJSON?.message || 'Failed to add item');
                    }
                });
            }

            // Remove item
            function removeItemFromSection(itemId) {
                if (!confirm('Remove this item from the section?')) return;

                $.ajax({
                    url: `/settings/sections/items/${itemId}`,
                    method: 'DELETE',
                    data: { _token: '{{ csrf_token() }}' },
                    success: function (response) {
                        toastr.success(response.message);
                        location.reload();
                    },
                    error: function () {
                        toastr.error('Failed to remove item');
                    }
                });
            }

            // Toggle featured
            function toggleFeatured(itemId, btn) {
                toastr.info('Feature toggle - Coming soon');
            }

            // Update order
            function updateItemsOrder() {
                let items = [];
                $('.current-item').each(function (index) {
                    items.push({
                        id: $(this).data('id'),
                        position: index + 1
                    });
                });

                $.ajax({
                    url: `/settings/sections/${sectionUuid}/update-items-order`,
                    method: 'POST',
                    data: {
                        _token: '{{ csrf_token() }}',
                        items: items
                    },
                    success: function (response) {
                        toastr.success(response.message);
                    },
                    error: function () {
                        toastr.error('Failed to update order');
                    }
                });
            }

            // Initial load
            renderCurrentItems();
            loadAvailableItems();
        });
    </script>
@endpush

@section('content')
    <!-- Page Header -->
    <div class="page-header">
        <div class="d-flex justify-content-between align-items-start">
            <div>
                <h4>{{ $title }}</h4>
                <div class="section-info">
                    <span class="info-badge">
                        <i class="bx bx-layout"></i>
                        <strong>{{ $section->name }}</strong>
                    </span>
                    <span class="info-badge">
                        <i class="bx bx-grid"></i>
                        {{ ucfirst($section->layout_type) }} Layout
                    </span>
                    <span class="info-badge">
                        <i class="bx bx-collection"></i>
                        Limit: {{ $section->items_limit }} items
                    </span>
                </div>
            </div>
            <div class="action-buttons">
                <a href="{{ route('sections.edit', $section->uuid) }}" class="btn btn-light">
                    <i class="bx bx-edit"></i> Edit Section
                </a>
                <a href="{{ route('sections.list') }}" class="btn btn-warning">
                    <i class="bx bx-arrow-back"></i> Back
                </a>
            </div>
        </div>
    </div>

    <!-- Main Grid -->
    <div class="manage-grid">
        <!-- Left Panel: Add Items -->
        <div class="panel">
            <div class="panel-header">
                <h5 class="panel-title">
                    <i class="bx bx-package"></i>
                    Add Items
                </h5>
            </div>
            <div class="panel-body">
                <div class="info-card">
                    <i class="bx bx-info-circle"></i>
                    Select a module type and add items to your section
                </div>

                <!-- Module Tabs -->
                <div class="module-tabs">
                    @foreach($availableModules as $key => $label)
                        <button class="module-tab {{ $loop->first ? 'active' : '' }}" data-module="{{ $key }}">
                            <span class="module-tab-icon">
                                @php
                                    $icons = ['news' => '📰', 'pages' => '📄', 'galleries' => '📸', 'leaders' => '👥'];
                                    echo $icons[$key] ?? '📦';
                                @endphp
                            </span>
                            <span>{{ $label }}</span>
                        </button>
                    @endforeach
                </div>

                <!-- Search -->
                <div class="search-box">
                    <input type="text" id="search-items" class="form-control" placeholder="Search items...">
                    <i class="bx bx-search"></i>
                </div>

                <!-- Available Items -->
                <div class="items-container" id="available-items-list">
                    <div class="loading-spinner">
                        <div class="spinner"></div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Right Panel: Current Items -->
        <div class="panel">
            <div class="panel-header">
                <h5 class="panel-title">
                    <i class="bx bx-list-ul"></i>
                    Current Items <span class="badge bg-primary">{{ $section->items->count() }}</span>
                </h5>
            </div>
            <div class="panel-body">
                <div class="info-card">
                    <i class="bx bx-info-circle"></i>
                    <strong>Drag</strong> to reorder • <strong>⭐</strong> to feature • <strong>🗑️</strong> to remove
                </div>

                <!-- Current Items List -->
                <div class="items-container" id="current-items-list">
                    @if($section->items->count() > 0)
                        @foreach($section->items as $index => $item)
                            @php
                                $moduleItem = $item->moduleItem;
                                $title = $moduleItem->title ?? $moduleItem->name ?? 'Untitled';
                                $image = $moduleItem->image ?? $moduleItem->thumbnail ?? $moduleItem->photo ?? $moduleItem->featured_image ?? null;
                            @endphp
                            <div class="current-item" data-id="{{ $item->id }}">
                                <div class="item-row">
                                    <span class="drag-handle">
                                        <i class="bx bx-menu"></i>
                                    </span>
                                    <div class="item-number">{{ $index + 1 }}</div>
                                    <div class="item-image">
                                        @if($image)
                                            <img src="{{ $image }}" alt="{{ $title }}">
                                        @else
                                            @php
                                                $icons = ['news' => '📰', 'pages' => '📄', 'galleries' => '📸', 'leaders' => '👥'];
                                                echo $icons[$item->module_type] ?? '📦';
                                            @endphp
                                        @endif
                                    </div>
                                    <div class="item-details">
                                        <div class="item-details-title">{{ $title }}</div>
                                        <div class="item-details-meta">
                                            <span class="module-badge">
                                                @php
                                                    $icons = ['news' => '📰', 'pages' => '📄', 'galleries' => '📸', 'leaders' => '👥'];
                                                    echo $icons[$item->module_type] ?? '📦';
                                                @endphp
                                                {{ ucfirst($item->module_type) }}
                                            </span>
                                            @if($item->is_featured)
                                                <span class="featured-badge">⭐ Featured</span>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="item-actions">
                                        <button class="btn-icon btn-star {{ $item->is_featured ? 'featured' : '' }}"
                                            data-id="{{ $item->id }}" title="Toggle Featured">
                                            <i class="bx {{ $item->is_featured ? 'bxs-star' : 'bx-star' }}"></i>
                                        </button>
                                        <button class="btn-icon btn-remove" data-id="{{ $item->id }}" title="Remove">
                                            <i class="bx bx-trash"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    @else
                        <div class="empty-state">
                            <div class="empty-state-icon">📦</div>
                            <div class="empty-state-title">No items added yet</div>
                            <div class="empty-state-text">Select a module and add items from the left panel</div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection