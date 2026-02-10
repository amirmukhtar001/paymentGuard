@php
    $departmentCount = count($departments);
    $onlyDepartmentId = $departmentCount === 1 ? array_key_first($departments) : null;
@endphp

<label for="{{ $select_id }}">{{ $label }}</label>

<select id="{{ $select_id }}" name="{{ $select_id }}" class="form-control" @if($departmentCount === 1) disabled @endif>

    @if($departmentCount > 1)
        <option value="">All {{ $label }}s</option>
    @endif

    @foreach($departments as $id => $name)
        <option value="{{ $id }}" @if($departmentCount === 1 && $id == $onlyDepartmentId) selected @endif>
            {{ $name }}
        </option>
    @endforeach
</select>

@if($departmentCount === 1)
    {{-- Disabled selects don't submit values in forms.
    This hidden input ensures the selected department ID is still available if you ever submit a form. --}}
    <input type="hidden" name="{{ $select_id }}" value="{{ $onlyDepartmentId }}">
@endif