@extends('layouts.' . config('settings.active_layout'))

@section('content')

<div class="row">
    <div class="col-12">

        <div class="card">
            <div class="card-header header-elements-inline">
                <h5 class="card-title">{{ $title }}</h5>
                <div class="header-elements"></div>
            </div>

            <div class="card-body">
                <div class="row">
                    <div class="col-12">

                        @php
                        /**
                        * period_year in query can be:
                        * - 'current' (default)
                        * - '2025'
                        */
                        $queryPeriod = request()->query('period_year', 'current');
                        $submitPeriod = ($queryPeriod === 'current' || $queryPeriod === '') ? '' : $queryPeriod;

                        $currentYear = (int) date('Y');
                        $deletePeriod = $queryPeriod ?: 'current';
                        @endphp

                        {{-- ✅ SAVE FORM --}}
                        <form method="POST"
                            action="{{ route('settings.districts.statistics.store', $district_id) }}">
                            @csrf

                            {{-- Submit scope safely --}}
                            <input type="hidden" name="period_year" value="{{ old('period_year', $submitPeriod) }}">

                            {{-- District (read only display) --}}
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="form-label">District</label>
                                        <input type="text"
                                            class="form-control"
                                            value="{{ $district->name ?? $district->title ?? $district_id }}"
                                            readonly>
                                    </div>
                                </div>

                                {{-- Period Year (navigation only) --}}
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label class="form-label">Year</label>

                                        <span class="help">
                                            @if($errors->has('period_year')) {!! $errors->first('period_year') !!} @endif
                                        </span>

                                        <select id="period_year_select" class="form-control">
                                            <option value="current" {{ ($queryPeriod === 'current' || $queryPeriod === '') ? 'selected' : '' }}>
                                                Current (No year)
                                            </option>

                                            @for($y = $currentYear; $y >= ($currentYear - 30); $y--)
                                            <option value="{{ $y }}" {{ (string)$queryPeriod === (string)$y ? 'selected' : '' }}>
                                                {{ $y }}
                                            </option>
                                            @endfor
                                        </select>

                                        <small class="text-muted">Changing year will reload this form.</small>
                                    </div>
                                </div>

                                {{-- Quick actions --}}
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label class="form-label d-block">&nbsp;</label>

                                        <a href="{{ route('settings.districts.show', $district_id) }}" class="btn btn-warning">
                                            <i class="bx bx-arrow-back tf-icons"></i> Back
                                        </a>

                                        <a href="{{ route('settings.districts.statistics.index', ['id' => $district_id]) }}"
                                            class="btn btn-label-primary">
                                            <i class="bx bx-list-ul"></i> List
                                        </a>
                                    </div>
                                </div>
                            </div>

                            <hr>

                            {{-- Dynamic statistics --}}
                            @php
                            $chunks = $definitions->chunk(3);
                            @endphp

                            @foreach($chunks as $chunk)
                            <div class="row">
                                @foreach($chunk as $def)
                                @php
                                $defId = $def->id;
                                $oldKey = "stats.$defId";
                                $val = old($oldKey, $existingValues[$defId] ?? '');

                                $isRequired = (bool) ($def->is_required ?? false);
                                $unit = $def->unit ? ' (' . $def->unit . ')' : '';

                                $step = $def->type === 'integer' ? '1' : 'any';
                                $min = !is_null($def->min) ? $def->min : null;
                                $max = !is_null($def->max) ? $def->max : null;

                                if ($def->type === 'percent') {
                                $min = $min ?? 0;
                                $max = $max ?? 100;
                                }
                                @endphp

                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label class="form-label {{ $isRequired ? 'req' : '' }}">
                                            {{ $def->label }}{!! $unit !!}
                                        </label>

                                        <span class="help">
                                            @if($errors->has($oldKey)) {!! $errors->first($oldKey) !!} @endif
                                        </span>

                                        @if($def->type === 'text')
                                        <input type="text"
                                            name="stats[{{ $defId }}]"
                                            class="form-control"
                                            value="{{ $val }}"
                                            {{ $isRequired ? 'required' : '' }}>
                                        @else
                                        <input type="number"
                                            name="stats[{{ $defId }}]"
                                            class="form-control"
                                            value="{{ $val }}"
                                            step="{{ $step }}"
                                            @if(!is_null($min)) min="{{ $min }}" @endif
                                            @if(!is_null($max)) max="{{ $max }}" @endif
                                            {{ $isRequired ? 'required' : '' }}>
                                        @endif

                                        <small class="text-muted">
                                            Key: {{ $def->key }} | Type: {{ $def->type }}
                                        </small>
                                    </div>
                                </div>
                                @endforeach
                            </div>
                            @endforeach

                            {{-- Buttons --}}
                            <div class="row mt-3">
                                <div class="col-12">
                                    <a href="{{ route('settings.districts.show', $district->id) }}" class="btn btn-warning">
                                        <i class="bx bx-arrow-back tf-icons"></i> Back
                                    </a>

                                    <button type="submit" class="btn btn-success">
                                        <i class="bx bx-save"></i> Save
                                    </button>
                                </div>
                            </div>

                        </form>
                        {{-- ✅ END SAVE FORM --}}

                        {{-- ✅ DELETE FORM (not nested) --}}
                        <form method="POST"
                            action="{{ route('settings.districts.statistics.destroy', ['id' => $district->id]) }}"
                            class="d-inline"
                            onsubmit="return confirm('Are you sure you want to delete these statistics?');">
                            @csrf
                            @method('DELETE')
                            <input type="hidden" name="period_year" value="{{ $deletePeriod }}">
                            <button type="submit" class="btn btn-danger mt-2">
                                <i class="bx bx-trash"></i> Delete This Scope
                            </button>
                        </form>

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
            const period = $(this).val(); // 'current' or '2025'
            let url = "{{ route('settings.districts.statistics.create', ['id' => $district_id]) }}";
            url += '?period_year=' + encodeURIComponent(period);
            window.location.href = url;
        });
    });
</script>
@endpush