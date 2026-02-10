@extends('layouts/layoutMaster')

@section('title', 'Invoice List - Pages')

@section('vendor-style')
@include('components.datatables.cdn-styles')

@endsection

@section('vendor-script')
<script src="{{asset('assets/vendor/libs/moment/moment.js')}}"></script>
@include('components.datatables.cdn-scripts')
@endsection

@section('page-script')
<script src="{{asset('assets/js/app-invoice-list.js')}}"></script>
@endsection

@section('content')
<h4 class="py-3 breadcrumb-wrapper mb-4">
  <span class="text-muted fw-light">Invoice /</span> List
</h4>

<!-- Invoice List Table -->
<div class="card">
  <div class="card-datatable table-responsive">
    <table class="invoice-list-table table border-top">
      <thead>
        <tr>
          <th></th>
          <th>#ID</th>
          <th><i class='bx bx-trending-up'></i></th>
          <th>Client</th>
          <th>Total</th>
          <th class="text-truncate">Issued Date</th>
          <th>Balance</th>
          <th>Invoice Status</th>
          <th class="cell-fit">Actions</th>
        </tr>
      </thead>
    </table>
  </div>
</div>
@endsection
