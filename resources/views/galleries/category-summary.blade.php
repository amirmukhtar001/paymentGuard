@extends('layouts.' . config('settings.active_layout'))

@push('stylesheets')
@include('components.datatables.cdn-styles')
@endpush

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header header-elements-inline">
                <h5 class="card-title">{{ $title }}</h5>
            </div>
            <div class="card-body">
                {{-- Filters --}}
                <div class="table-responsive" style="min-height: 200px">
                    <table class="table table-bordered table-striped" id="categorySummaryTable">
                        <thead>
                            <tr>
                                <th>Category</th>
                                <th>Total Images</th>
                                <th>Action</th>
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

        let table = $('#categorySummaryTable').DataTable({
            processing: true,
            serverSide: true,
            ajax: "{{ route('settings.galleries.category-summary.datatable') }}",
            columns: [{
                    data: 'category_title',
                    name: 'category_title'
                },
                {
                    data: 'total',
                    name: 'total'
                },
                {
                    data: 'action',
                    name: 'action',
                    orderable: false,
                    searchable: false
                },
            ],
        });

        $('#filter_company_id, #filter_category_id, #filter_featured, #filter_status, #filter_type').on('change', function() {
            table.ajax.reload();
        });
    });
</script>
@endpush