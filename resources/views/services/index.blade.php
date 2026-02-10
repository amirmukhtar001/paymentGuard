@extends('layouts.' . config('settings.active_layout'))

@push('stylesheets')
    @include('components.datatables.cdn-styles')
@endpush

@section('content')
    <div class="layout-top-spacing mb-2 m-2">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center flex-wrap gap-2">
                    <h5 class="card-title mb-0">{{ $title }}</h5>
                    <div>
                        <button type="button" class="btn btn-primary" onclick="window.location='{{ route('settings.services.create') }}'">
                            <i class="bx bx-plus"></i> New Service
                        </button>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row mb-3">
                        <div class="col-md-3">
                            @include('components.companies', ['companies' => $companies,'select_id' => 'filter_company_id','label' => 'Website', 'selected_company_id' => $selected_company_id])
                        </div>
                        <div class="col-md-3">
                            <label for="filter_department_id" class="form-label">Department</label>
                            <select id="filter_department_id" class="form-control">
                                <option value="">All Departments</option>
                                @foreach($departments ?? [] as $id => $name)
                                    <option value="{{ $id }}">{{ $name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label for="filter_parent_category_id" class="form-label">Parent Category</label>
                            <select id="filter_parent_category_id" class="form-control">
                                <option value="">All Parent Categories</option>
                                @foreach($categories as $id => $name)
                                    <option value="{{ $id }}">{{ $name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label for="filter_sub_category_id" class="form-label">Sub Category</label>
                            <select id="filter_sub_category_id" class="form-control">
                                <option value="">All Sub Categories</option>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label for="filter_status" class="form-label">Status</label>
                            <select id="filter_status" class="form-control">
                                <option value="">All Statuses</option>
                                <option value="draft">Draft</option>
                                <option value="pending">Pending</option>
                                <option value="publish">Publish</option>
                            </select>
                        </div>
                    </div>

                    <div class="table-responsive">
                        <table id="services-table" class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th>Actions</th>
                                    <th>Title</th>
                                    <th>Website</th>
                                    <th>Department</th>
                                    <th>Category</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody></tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    @include('components.datatables.cdn-scripts')
    <script>
        $(document).ready(function() {
            let table = $('#services-table').DataTable({
                processing: true,
                serverSide: true,
                ajax: {
                    url: '{{ route('settings.services.datatable') }}',
                    data: function(d) {
                        d.status = $('#filter_status').val();
                        d.company_id = $('#filter_company_id').val();
                        d.department_id = $('#filter_department_id').val();
                        d.category_id = $('#filter_sub_category_id').val() || $('#filter_parent_category_id').val();
                    }
                },
                columns: [{
                        data: 'actions',
                        name: 'actions',
                        orderable: false,
                        searchable: false,
                        className: 'text-center'
                    },
                    {
                        data: 'title',
                        name: 'title'
                    },
                    {
                        data: 'company_name',
                        name: 'company.title',
                        defaultContent: '-'
                    },
                    {
                        data: 'department_name',
                        name: 'department.name',
                        defaultContent: '-'
                    },
                    {
                        data: 'category_name',
                        name: 'category_name',
                        defaultContent: '-'
                    },
                    {
                        data: 'status_badge',
                        name: 'status',
                        orderable: true,
                        searchable: false,
                        className: 'text-center'
                    }
                ],
                order: [
                    [1, 'asc']
                ],
                pageLength: 20,
                language: {
                    processing: '<div class="spinner-border text-primary" role="status"><span class="visually-hidden">Loading...</span></div>',
                }
            });

            $('#filter_status, #filter_company_id, #filter_department_id').on('change', function() {
                table.ajax.reload();
            });

            const filterParentCategory = $('#filter_parent_category_id');
            const filterSubCategory = $('#filter_sub_category_id');

            filterParentCategory.on('change', function() {
                const parentId = $(this).val();
                if (!parentId) {
                    filterSubCategory.html('<option value="">All Sub Categories</option>');
                    table.ajax.reload();
                    return;
                }

                $.ajax({
                    url: "{{ route('settings.services.get-categories') }}",
                    type: 'GET',
                    data: { parent_id: parentId },
                    success: function(data) {
                        let html = '<option value="">All Sub Categories</option>';
                        $.each(data, function(id, name) {
                            html += `<option value="${id}">${name}</option>`;
                        });
                        filterSubCategory.html(html);
                        table.ajax.reload();
                    }
                });
            });

            filterSubCategory.on('change', function() {
                table.ajax.reload();
            });
        });
    </script>
@endpush
