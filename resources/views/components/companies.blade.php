@php
$companyCount = count($companies);
$onlyCompanyId = $companyCount === 1 ? array_key_first($companies) : null;
$selectedCompanyId = $selected_company_id ?? null;
@endphp

<label for="{{ $select_id }}">{{ $label }}</label>

<select id="{{ $select_id }}"
    name="{{ $select_id }}"
    class="form-control"
    @if($companyCount===1) disabled @endif>

    @if($companyCount > 1)
    <option value="">All {{ $label }}s</option>
    @endif

    @foreach($companies as $id => $name)
    <option value="{{ $id }}"
        @if(($companyCount===1 && $id==$onlyCompanyId) || ($selectedCompanyId && $id==$selectedCompanyId)) selected @endif>
        {{ $name }}
    </option>
    @endforeach
</select>

@if($companyCount === 1)
{{-- Disabled selects don't submit values in forms.
         This hidden input ensures the selected company ID is still available if you ever submit a form. --}}
<input type="hidden" name="{{ $select_id }}" value="{{ $onlyCompanyId }}">
@endif
