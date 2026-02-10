@extends('layouts.' . config('settings.active_layout'))

@section('content')

<div class="row">
    <div class="col-12">

        <div class="card">
            <div class="card-header header-elements-inline">
                <h5 class="card-title">{{ $title ?? 'View District' }}  {{ $item->name ?? $item->title ?? '-' }}</h5>
                <div class="header-elements"></div>
            </div>

            <div class="card-body">

                {{-- District basic info --}}
                

                <hr>

                {{-- Statistics block --}}
                @php
                /**
                * period_year behavior:
                * - null/'' => All years + Current
                * - 'current' => Only current (period_year NULL)
                * - 2025 => Only that year
                */
                $selectedPeriod = request()->query('period_year', '');
                $showingText = 'All years';

                if ($selectedPeriod === 'current') {
                $showingText = 'Current (No year)';
                } elseif (!empty($selectedPeriod)) {
                $showingText = $selectedPeriod;
                }

                $currentYear = (int) date('Y');
                @endphp

                <div class="row align-items-end">
                    <div class="col-md-6">
                        <h6 class="mb-2">District <i>{{ $item->name ?? $item->title ?? '-' }} </i> Statistics</h6>
                        <p class="text-muted mb-2">
                            Showing: <strong>{{ $showingText }}</strong>
                        </p>

                        <div class="row">
                            <div class="col-md-7">
                                <div class="form-group mb-0">
                                    <label class="form-label">Filter by Year</label>
                                    <select id="period_year_select" class="form-control">
                                        <option value="" {{ $selectedPeriod === '' ? 'selected' : '' }}>
                                            All years
                                        </option>

                                        <option value="current" {{ $selectedPeriod === 'current' ? 'selected' : '' }}>
                                            Current (No year)
                                        </option>

                                        @for($y = $currentYear; $y >= ($currentYear - 30); $y--)
                                        <option value="{{ $y }}" {{ (string)$selectedPeriod === (string)$y ? 'selected' : '' }}>
                                            {{ $y }}
                                        </option>
                                        @endfor
                                    </select>
                                </div>
                            </div>
                        </div>

                    </div>

                    <div class="col-md-6 text-end">
                        <a href="{{ route('settings.districts.statistics.index', ['id' => $district_id]) }}"
                            class="btn btn-label-primary me-2">
                            <i class="bx bx-list-ul"></i> View All
                        </a>

                        <a href="{{ route('settings.districts.statistics.create', ['id' => $district_id]) }}"
                            class="btn btn-primary">
                            <i class="bx bx-plus"></i> Add / Update
                        </a>
                    </div>
                </div>

                <div class="row mt-3">
                    <div class="col-12">
                        <div class="table-responsive">
                            <table class="table table-bordered table-striped">
                                <thead>
                                    <tr>
                                        <th>Stat</th>
                                        <th style="width: 220px;">Value</th>
                                        <th style="width: 120px;">Unit</th>
                                        <th style="width: 120px;">Year</th>
                                    </tr>
                                </thead>

                                <tbody>
                                    @forelse($stats as $row)
                                    @php
                                    $val = $row->value_number ?? $row->value_text ?? '-';
                                    $unit = $row->statDefinition->unit ?? '-';
                                    $year = $row->period_year ?? 'Current';
                                    @endphp
                                    <tr>
                                        <td>
                                            <strong>{{ $row->statDefinition->label ?? '-' }}</strong>
                                            <div class="text-muted small">{{ $row->statDefinition->key ?? '' }}</div>
                                        </td>
                                        <td>{{ $val }}</td>
                                        <td>{{ $unit }}</td>
                                        <td>{{ $year }}</td>
                                    </tr>
                                    @empty
                                    <tr>
                                        <td colspan="4" class="text-center text-muted">
                                            No statistics found for this district.
                                            <a href="{{ route('settings.districts.statistics.create', ['id' => $district_id]) }}">
                                                Add statistics
                                            </a>
                                        </td>
                                    </tr>
                                    @endforelse
                                </tbody>

                            </table>
                        </div>

                        @if(!empty($statsUpdatedAt))
                        <small class="text-muted">
                            Last updated: {{ $statsUpdatedAt }}
                        </small>
                        @endif
                    </div>
                </div>

                {{-- Buttons --}}
                <div class="row mt-3">
                    <div class="col-12">
                        <a href="{{ route('settings.districts.list') }}" class="btn btn-warning">
                            <i class="bx bx-arrow-back tf-icons"></i> Back
                        </a>
                    </div>
                </div>

            </div>
        </div>

    </div>
</div>

@endsection

@push('scripts')
<script>
    $(document).ready(function() {

        $('#period_year_select').on('change', function() {
            const period = $(this).val();

            // base url to district show page (uuid binding)
            const baseUrl = "{{ route('settings.districts.show', $district_id) }}";

            if (!period) {
                // All years: remove query string
                window.location.href = baseUrl;
                return;
            }

            // Add query string
            window.location.href = baseUrl + '?period_year=' + encodeURIComponent(period);
        });

    });
</script>
@endpush