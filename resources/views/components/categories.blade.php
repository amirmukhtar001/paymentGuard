@php
$categoryCount = count($categories);
$onlyCategoryId = $categoryCount === 1 ? array_key_first($categories) : null;

$selectedValue = old($select_id, $selected ?? '');
@endphp

<select id="{{ $select_id }}"
    name="{{ $select_id }}"
    class="form-control"
    @if($categoryCount===1) disabled @endif>

    @if($categoryCount > 1)
    <option value="">{{ $placeholder ?? ('All ' . $label . 's') }}</option>
    @endif

    @foreach($categories as $id => $name)
    <option value="{{ $id }}"
        @if($categoryCount===1 && $id==$onlyCategoryId) selected @endif
        @if($categoryCount>1 && (string)$selectedValue === (string)$id) selected @endif>
        {{ $name }}
    </option>
    @endforeach
</select>

@if($categoryCount === 1)
{{-- Disabled selects don't submit values in forms --}}
<input type="hidden" name="{{ $select_id }}" value="{{ $onlyCategoryId }}">
@endif