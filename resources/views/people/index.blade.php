@extends('layouts.' . config('settings.active_layout'))

@push('stylesheets')
@include('components.datatables.cdn-styles')
@endpush

@section('content')
<div class="row">
    <div class="col-12">

        <div class="card">
            <div class="card-body">

                {{-- Filters --}}
                <div class="row mb-3">

                    <div class="col-md-2">
                        <div class="form-group">
                            <label for="filter_person_type">Type</label>
                            <select id="filter_person_type" class="form-control">
                                <option value="">All</option>
                                <option value="athlete">Athlete</option>
                                <option value="poet">Poet</option>
                                <option value="hero">Hero</option>
                                <option value="scholar">Scholar</option>
                                <option value="artist">Artist</option>
                                <option value="other">Other</option>
                            </select>
                        </div>
                    </div>

                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="filter_category_id">Category</label>
                            <select id="filter_category_id" class="form-control">
                                <option value="">All Categories</option>
                                <option value="0">None</option>
                                @foreach($categories as $id => $name)
                                <option value="{{ $id }}">{{ $name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="filter_section_id">Section</label>
                            <select id="filter_section_id" class="form-control">
                                <option value="">All Sections</option>
                                <option value="0">None</option>
                                @foreach($sections as $id => $name)
                                <option value="{{ $id }}">{{ $name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="col-md-2">
                        <div class="form-group">
                            <label for="filter_is_featured">Featured</label>
                            <select id="filter_is_featured" class="form-control">
                                <option value="">All</option>
                                <option value="yes">Yes</option>
                                <option value="no">No</option>
                            </select>
                        </div>
                    </div>

                    <div class="col-md-2">
                        <div class="form-group">
                            <label for="filter_status">Status</label>
                            <select id="filter_status" class="form-control">
                                <option value="">All</option>
                                <option value="draft">Draft</option>
                                <option value="published">Published</option>
                                <option value="archived">Archived</option>
                            </select>
                        </div>
                    </div>

                </div>

                <div class="table-responsive">
                    <table id="people-table" class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th style="width:40px;">⇅</th>
                                <th style="width:160px;">Actions</th>
                                <th>Image</th>
                                <th>Name</th>
                                <th>Type</th>
                                <th>Category</th>
                                <th>Section</th>
                                <th>Featured</th>
                                <th>Status</th>
                                <th>Order</th>
                            </tr>
                        </thead>

                    </table>
                </div>

            </div>
        </div>

    </div>
</div>
@endsection

@push('scripts')
@include('components.datatables.cdn-scripts')

<script type="text/javascript">
    $(document).ready(function() {

        let isReordering = false;

        let table = $('#people-table').DataTable({
            processing: true,
            serverSide: true,

            rowId: 'id',
            order: [
                [9, 'asc']
            ], // display_order column index

            ajax: {
                url: '{{ route("settings.people.datatable") }}',
                data: function(d) {
                    d.person_type = $('#filter_person_type').val();
                    d.category_id = $('#filter_category_id').val();
                    d.section_id = $('#filter_section_id').val();
                    d.is_featured = $('#filter_is_featured').val();
                    d.status = $('#filter_status').val();
                }
            },

            rowReorder: {
                selector: '.reorder-handle'
            },

            columnDefs: [{
                    targets: 0,
                    orderable: false,
                    searchable: false
                }, // handle column
            ],

            columns: [{
                    data: null,
                    defaultContent: '<span class="reorder-handle">⇅</span>',
                    orderable: false,
                    searchable: false
                },
                {
                    data: 'actions',
                    name: 'actions',
                    orderable: false,
                    searchable: false
                },
                {
                    data: 'profile_media',
                    name: 'media.file_path',
                    orderable: false,
                    searchable: false
                },
                {
                    data: 'name',
                    name: 'people.name'
                },
                {
                    data: 'person_type',
                    name: 'people.person_type'
                },
                {
                    data: 'category_name',
                    name: 'category.name',
                    defaultContent: '-'
                },
                {
                    data: 'section_name',
                    name: 'section.name',
                    defaultContent: '-'
                },
                {
                    data: 'featured_badge',
                    name: 'people.is_featured',
                    orderable: false,
                    searchable: false
                },
                {
                    data: 'status',
                    name: 'people.status',
                    searchable: false
                },

                // actual sort column
                {
                    data: 'display_order',
                    name: 'people.display_order'
                },
            ],

            pageLength: 20,
            lengthMenu: [
                [20, 50, 100, 500, 1000],
                [20, 50, 100, 500, 1000]
            ],
            dom: '<"card-header flex-column flex-md-row"<"head-label"><"dt-action-buttons text-end pt-3 pt-md-0"B>>' +
                '<"row"<"col-sm-12 col-md-6"l><"col-sm-12 col-md-6 d-flex justify-content-center justify-content-md-end"f>>' +
                'rt' +
                '<"row"<"col-sm-12 col-md-6"i><"col-sm-12 col-md-6"p>>',
            buttons: [{
                text: '<i class="bx bx-plus me-sm-1"></i> <span class="d-none d-sm-inline-block">New Person</span>',
                className: 'btn btn-primary',
                action: function() {
                    window.location.href = "{{ route('settings.people.create') }}";
                }
            }],
            language: {
                processing: '<div class="spinner-border text-primary" role="status"><span class="visually-hidden">Loading...</span></div>',
                emptyTable: 'No people found',
                zeroRecords: 'No matching people found'
            },
            responsive: true,
        });

        // filters
        $('#filter_person_type, #filter_category_id, #filter_section_id, #filter_is_featured, #filter_status')
            .on('change', function() {
                table.ajax.reload();
            });

        // ✅ reorder handler - FIXED VERSION
        table.on('row-reorder', function(e, diff, edit) {
            if (!diff.length || isReordering) return;

            isReordering = true;

            // ❌ OLD WAY - Gets stale data from DataTables cache
            // const rows = table.rows({ page: 'current', order: 'applied' }).data().toArray();

            // ✅ NEW WAY - Get actual DOM order after drag-and-drop
            const items = [];
            $('#people-table tbody tr').each(function(index) {
                const rowData = table.row(this).data();
                if (rowData && rowData.id) {
                    items.push({
                        id: rowData.id,
                        display_order: index + 1
                    });
                }
            });

            $.ajax({
                url: '{{ route("settings.people.reorder") }}',
                type: 'POST',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                data: {
                    items: items
                },
                success: function() {
                    table.ajax.reload(null, false);
                },
                error: function(xhr) {
                    console.log('Reorder error:', xhr.responseText);
                    table.ajax.reload(null, false);
                },
                complete: function() {
                    isReordering = false;
                }
            });
        });

    });
</script>

@endpush