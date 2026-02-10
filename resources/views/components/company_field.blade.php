@php
$readonly = false;
if(isset($disable) && !empty($disable)){
$readonly = true;
}
$companyCount = count($companies);
$onlyCompanyId = $companyCount === 1 ? array_key_first($companies) : null;

// Selected value priority:
// 1) old input (after validation)
// 2) passed selected value (edit)
// 3) if only 1 company, auto select it
$selected = old($select_id, $selected ?? ($onlyCompanyId ?? ''));
@endphp
<select id="{{ $select_id }}"
    name="{{ $select_id }}" class="form-control " @if($companyCount===1 || $readonly===true) disabled @endif>

    @if($companyCount > 1)
    <option value="">All {{ $label }}s</option>
    @endif

    @foreach($companies as $id => $name)
    <option value="{{ $id }}" {{ (string) $selected === (string)$id ? 'selected' : '' }}>
        {{ $name }}
    </option>
    @endforeach
</select>

@if($companyCount === 1 || $readonly===true)
{{-- Disabled selects don't submit values --}}
<input type="hidden" name="{{ $select_id }}" value="{{ $selected }}">
@endif