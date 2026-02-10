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
                            <label for="filter_is_featured">Featured</label>
                            <select id="filter_is_featured" class="form-control">
                                <option value="">All</option>
                                <option value="yes">Yes</option>
                                <option value="no">No</option>
                            </select>
                        </div>
                    </div>

                    <div class="col-md-3">
                        <div class="form-group">
                             @include('components.companies', ['companies' => $companies, 'select_id' => 'filter_company_id', 'label' => 'Web Site', 'selected_company_id' => $selected_company_id])
                        </div>
                    </div>
                </div>


                <div class="table-responsive">
                    <table id="people-table" class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th style="width:40px;">⇅</th>
                                <th style="width:160px;">Actions</th>
                                <th>Name</th>
                                <th>Preview</th>
                                <th>Type</th>
                                <th>Department</th>
                                <th>Status</th>

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

        let table = $('#people-table').DataTable({
            processing: true,
            serverSide: true,
            rowId: 'id', // ✅ IMPORTANT
            order: [
                [0, 'asc']
            ], // ✅ Always order by sort_order
            ajax: {
                url: '{{ route("leaders.datatable") }}',
                data: function(d) {
                    d.is_featured = $('#filter_is_featured').val();
                    d.company_id = $('#filter_company_id').val();
                }
            },
            rowReorder: {
                selector: '.reorder-handle'
            },
            columnDefs: [{
                    targets: 0,
                    visible: true
                } // hide sort_order column (optional)
            ],
            columns: [{
                    data: null,
                    defaultContent: '<span class="reorder-handle">⇅</span>',
                    orderable: false,
                    searchable: false
                },
                {
                    data: 'actions',
                    orderable: false,
                    searchable: false
                },
                {
                    data: 'name',
                    name: 'name'
                },
                {
                    data: 'preview',
                    name: 'preview'
                },
                {
                    data: 'type',
                    name: 'leaderable_type'
                },

                {
                    data: 'department',
                    name: 'department'
                },
                {
                    data: 'status',
                    name: 'is_active'
                },
            ],
        });

        // ✅ Only bind to existing filter
        $('#filter_is_featured, #filter_company_id').on('change', function() {
            table.ajax.reload();
        });


        // ✅ Reorder handler

        table.on('row-reorder', function(e, diff, edit) {
            if (!diff.length) return;

            let companyId = $('#filter_company_id').val();
            if (!companyId) {
                alert('Please select a Company first to reorder within that company.');
                table.ajax.reload(null, false); // reset view
                return;
            }

            requestAnimationFrame(function() {
                let items = [];
                $('#people-table tbody tr').each(function(index) {
                    let rowData = table.row(this).data();
                    if (rowData && rowData.id) {
                        items.push({
                            id: rowData.id,
                            sort_order: index + 1
                        });
                    }
                });

                $.ajax({
                    url: '{{ route("leaders.reorder") }}',
                    type: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    data: {
                        company_id: companyId,
                        items: items
                    },
                    success: function() {
                        table.ajax.reload(null, false);
                    }
                });
            });
        });


    });
</script>
@endpush