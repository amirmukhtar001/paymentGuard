@extends('layouts.' . config('settings.active_layout'))

@push('styles')
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.4/css/dataTables.bootstrap5.min.css">
    <style>
        .section-card {
            border: 1px solid #e0e0e0;
            border-radius: 8px;
            padding: 20px;
            margin-bottom: 15px;
            background: #fff;
            transition: all 0.3s ease;
            cursor: move;
        }

        .section-card:hover {
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
            transform: translateY(-2px);
        }

        .section-card.dragging {
            opacity: 0.5;
            border: 2px dashed #666;
        }

        .drag-handle {
            cursor: move;
            color: #999;
            font-size: 24px;
            padding: 0 10px;
        }

        .drag-handle:hover {
            color: #666;
        }

        .section-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 10px;
        }

        .section-title {
            display: flex;
            align-items: center;
            gap: 10px;
            font-size: 18px;
            font-weight: 600;
            color: #333;
        }

        .section-meta {
            display: flex;
            gap: 15px;
            color: #666;
            font-size: 14px;
            margin-bottom: 15px;
        }

        .section-meta span {
            display: flex;
            align-items: center;
            gap: 5px;
        }

        .status-badge {
            display: inline-flex;
            align-items: center;
            gap: 5px;
            padding: 4px 12px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 500;
        }

        .status-badge.active {
            background: #d4edda;
            color: #155724;
        }

        .status-badge.inactive {
            background: #f8d7da;
            color: #721c24;
        }

        .status-dot {
            width: 8px;
            height: 8px;
            border-radius: 50%;
        }

        .status-dot.active {
            background: #28a745;
        }

        .status-dot.inactive {
            background: #dc3545;
        }

        .section-actions {
            display: flex;
            gap: 8px;
        }

        .module-icon {
            font-size: 20px;
        }

        .filter-section {
            background: #f8f9fa;
            padding: 20px;
            border-radius: 8px;
            margin-bottom: 20px;
        }

        .empty-state {
            text-align: center;
            padding: 60px 20px;
            color: #999;
        }

        .empty-state i {
            font-size: 64px;
            margin-bottom: 20px;
            opacity: 0.3;
        }
    </style>
@endpush

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/sortablejs@1.15.0/Sortable.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.4/js/dataTables.bootstrap5.min.js"></script>
    <script>
        $(document).ready(function () {
            let sectionsContainer = document.getElementById('sections-container');
            let sortable = null;

            // Initialize Sortable for drag and drop
            function initSortable() {
                if (sortable) {
                    sortable.destroy();
                }

                sortable = new Sortable(sectionsContainer, {
                    animation: 150,
                    handle: '.drag-handle',
                    ghostClass: 'dragging',
                    onEnd: function (evt) {
                        updateSectionOrder();
                    }
                });
            }

            // Update section order via AJAX
            function updateSectionOrder() {
                let orders = [];
                $('.section-card').each(function (index) {
                    orders.push({
                        id: $(this).data('id'),
                        position: index + 1
                    });
                });

                $.ajax({
                    url: '{{ route("sections.update-order") }}',
                    method: 'POST',
                    data: {
                        _token: '{{ csrf_token() }}',
                        orders: orders
                    },
                    success: function (response) {
                        toastr.success(response.message);
                    },
                    error: function (xhr) {
                        toastr.error('Failed to update order');
                    }
                });
            }

            // Load sections
            function loadSections() {
                let pageType = $('#filter-page-type').val();
                let status = $('#filter-status').val();

                $.ajax({
                    url: '{{ route("sections.datatable") }}',
                    method: 'GET',
                    data: {
                        page_type: pageType,
                        status: status
                    },
                    success: function (response) {
                        renderSections(response.data);
                    }
                });
            }

            // Render sections as cards
            function renderSections(sections) {
                sectionsContainer.innerHTML = '';

                if (sections.length === 0) {
                    sectionsContainer.innerHTML = `
                                <div class="empty-state">
                                    <i class="bx bx-folder-open"></i>
                                    <h4>No sections found</h4>
                                    <p>Create your first section to get started</p>
                                    <a href="{{ route('sections.create') }}" class="btn btn-primary mt-3">
                                        <i class="bx bx-plus"></i> Create Section
                                    </a>
                                </div>
                            `;
                    return;
                }

                sections.forEach(section => {
                    let moduleIcon = getModuleIcon(section.page_type);
                    let statusClass = section.status;
                    let statusText = section.status.charAt(0).toUpperCase() + section.status.slice(1);

                    let card = `
                                <div class="section-card" data-id="${section.id}">
                                    <div class="section-header">
                                        <div class="section-title">
                                            <span class="drag-handle">
                                                <i class="bx bx-menu"></i>
                                            </span>
                                            <span class="module-icon">${moduleIcon}</span>
                                            <span>${section.name}</span>
                                        </div>
                                        <span class="status-badge ${statusClass}">
                                            <span class="status-dot ${statusClass}"></span>
                                            ${statusText}
                                        </span>
                                    </div>

                                    <div class="section-meta">
                                        <span>
                                            <i class="bx bx-layout"></i>
                                            ${section.layout_type}
                                        </span>
                                        <span>
                                            <i class="bx bx-collection"></i>
                                            ${section.items_count} / ${section.items_limit} items
                                        </span>
                                        <span>
                                            <i class="bx bx-sort"></i>
                                            Order: ${section.display_order}
                                        </span>
                                    </div>

                                    <div class="section-actions">
                                        <a href="/settings/sections/${section.uuid}/manage-items" 
                                           class="btn btn-sm btn-info" 
                                           title="Manage Items">
                                            <i class="bx bx-list-ul"></i> Manage Items
                                        </a>
                                        <a href="/settings/sections/${section.uuid}/edit" 
                                           class="btn btn-sm btn-primary" 
                                           title="Edit">
                                            <i class="bx bx-edit"></i> Edit
                                        </a>
                                        <button type="button" 
                                                class="btn btn-sm btn-danger delete-btn" 
                                                data-uuid="${section.uuid}"
                                                title="Delete">
                                            <i class="bx bx-trash"></i> Delete
                                        </button>
                                    </div>
                                </div>
                            `;

                    sectionsContainer.innerHTML += card;
                });

                initSortable();
                attachDeleteHandlers();
            }

            // Get module icon
            function getModuleIcon(pageType) {
                const icons = {
                    'home': '🏠',
                    'about': 'ℹ️',
                    'contact': '📞',
                    'custom': '⚙️'
                };
                return icons[pageType] || '📄';
            }

            // Attach delete handlers
            function attachDeleteHandlers() {
                $('.delete-btn').off('click').on('click', function () {
                    let uuid = $(this).data('uuid');
                    let url = '/settings/sections/' + uuid;

                    if (confirm('Are you sure you want to delete this section?')) {
                        $.ajax({
                            url: url,
                            method: 'DELETE',
                            data: {
                                _token: '{{ csrf_token() }}'
                            },
                            success: function (response) {
                                toastr.success(response.message);
                                loadSections();
                            },
                            error: function (xhr) {
                                toastr.error('Failed to delete section');
                            }
                        });
                    }
                });
            }

            // Filter handlers
            $('#filter-page-type, #filter-status').on('change', function () {
                loadSections();
            });

            // Initial load
            loadSections();
        });
    </script>
@endpush

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="card-title mb-0">{{ $title }}</h5>
                    <a href="{{ route('sections.create') }}" class="btn btn-primary">
                        <i class="bx bx-plus"></i> Create Section
                    </a>
                </div>

                <div class="card-body">
                    <!-- Filters -->
                    <div class="filter-section">
                        <div class="row">
                            <div class="col-md-4">
                                <label class="form-label">Page Type</label>
                                <select id="filter-page-type" class="form-control">
                                    <option value="">All Pages</option>
                                    <option value="home">Home</option>
                                    <option value="about">About</option>
                                    <option value="contact">Contact</option>
                                    <option value="custom">Custom</option>
                                </select>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Status</label>
                                <select id="filter-status" class="form-control">
                                    <option value="">All Status</option>
                                    <option value="active">Active</option>
                                    <option value="inactive">Inactive</option>
                                </select>
                            </div>
                            <div class="col-md-4 d-flex align-items-end">
                                <div class="alert alert-info mb-0 w-100">
                                    <i class="bx bx-info-circle"></i>
                                    Drag sections to reorder them
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Sections Container -->
                    <div id="sections-container">
                        <!-- Sections will be loaded here via AJAX -->
                        <div class="text-center py-5">
                            <div class="spinner-border" role="status">
                                <span class="visually-hidden">Loading...</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection