@extends('layouts.' . config('settings.active_layout'))

@push('stylesheets')
@include('components.datatables.cdn-styles')
@endpush


@php #filter_company_id
$categoryQuery = null;

if(isset($uuid))
{
$categoryQuery = '?q='.$uuid;
}
@endphp

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header header-elements-inline">
                <h5 class="card-title">{{ $title }}</h5>
            </div>
            <div class="card-body">
                {{-- Filters --}}
                <div class="row mb-3">
                    <div class="col-md-3">
                        @include('components.companies', [
                        'companies' => $companies,
                        'select_id' => 'filter_company_id',
                        'label' => 'Web Site',
                        'selected_company_id' => $selected_company_id
                        ])
                    </div>
                    <div class="col-md-3"> <label for="filter_featured">Select Category</label>
                        @include('components.categories', [
                        'categories' => $categories,
                        'select_id' => 'filter_category_id',
                        'label' => 'Category',

                        'selected' => $categoryId ?? null
                        ])
                    </div>
                    <div class="col-md-2">
                        <div class="form-group">
                            <label for="filter_featured">Featured</label>
                            <select id="filter_featured" class="form-control">
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
                    <div class="col-md-2">
                        <div class="form-group">
                            <label for="filter_type">Type</label>
                            <select id="filter_type" class="form-control">
                                <option value="">All</option>
                                <option value="image">Image</option>
                                <option value="video">Video</option>
                                <option value="mixed">Urls</option>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="table-responsive" style="min-height: 200px">
                    <table id="galleries-table" class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th style="width: 160px;">Actions</th>
                                <th>Name</th>
                                <th>Slug</th>
                                <th>Type</th>
                                <th>Featured</th>
                                <th>Status</th>
                                <th>Media</th>
                                <th>Category</th>
                                <th>Web Site</th>
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

        const preselectedCategoryId = @json($categoryId ?? '');

        if (preselectedCategoryId) {
            $('#filter_category_id').val(preselectedCategoryId).trigger('change.select2');
            $('#filter_category_id').prop('disabled', true); // optional
        }

        let table = $('#galleries-table').DataTable({
            processing: true,
            serverSide: true,
            ajax: {
                url: '{{ route("settings.galleries.datatable") }}',
                data: function(d) {
                    d.company_id = $('#filter_company_id').val();
                    d.category_id = $('#filter_category_id').val();
                    d.is_featured = $('#filter_featured').val();
                    d.status = $('#filter_status').val();
                    d.type = $('#filter_type').val();
                }
            },
            columns: [{
                    data: 'actions',
                    name: 'actions',
                    orderable: false,
                    searchable: false
                },
                {
                    data: 'name',
                    name: 'galleries.name'
                },
                {
                    data: 'slug',
                    name: 'galleries.slug'
                },
                {
                    data: 'type',
                    name: 'galleries.type'
                },
                {
                    data: 'featured_badge',
                    name: 'galleries.is_featured',
                    searchable: false
                },
                {
                    data: 'status',
                    name: 'galleries.status',
                    searchable: false
                },
                {
                    data: 'media_display',
                    name: 'galleries.media_url',
                    orderable: false,
                    searchable: false
                },
                {
                    data: 'category_name',
                    name: 'category.name',
                    defaultContent: '-'
                },
                {
                    data: 'company_name',
                    name: 'company.title',
                    defaultContent: '-'
                },
            ],
            order: [
                [1, 'desc']
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
            buttons: [
                {
                    text: '<i class="bx bx-plus me-sm-1"></i> <span class="d-none d-sm-inline-block">New Gallery</span>',
                    className: 'btn btn-primary',
                    action: function() {
                        window.location.href = "{{ route('settings.galleries.create').$categoryQuery }}";
                    }
                }
            ],
            language: {
                // processing: '<div class="spinner-border text-primary" role="status"><span class="visually-hidden">Loading...</span></div>',
                emptyTable: 'No galleries found',
                zeroRecords: 'No matching galleries found'
            },
            responsive: true,
        });



        $('#filter_company_id, #filter_category_id, #filter_featured, #filter_status, #filter_type').on('change', function() {
            table.ajax.reload();
        });
    });
</script>
@endpush