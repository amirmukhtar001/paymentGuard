@php
$sectionCount = count($sections);
$onlysectionId = $sectionCount === 1 ? array_key_first($sections) : null;

$selectedValue = old($select_id, $selected ?? '');
@endphp

<select id="{{ $select_id }}"
    name="{{ $select_id }}"
    class="form-control"
    @if($sectionCount===1) disabled @endif>

    @if($sectionCount > 1)
    <option value="">{{ $placeholder ?? ('All ' . $label . 's') }}</option>
    @endif

    @foreach($sections as $id => $name)
    <option value="{{ $id }}"
        @if($sectionCount===1 && $id==$onlysectionId) selected @endif
        @if($sectionCount>1 && (string)$selectedValue === (string)$id) selected @endif>
        {{ $name }}
    </option>
    @endforeach
</select>

@if($sectionCount === 1)
{{-- Disabled selects don't submit values in forms --}}
<input type="hidden" name="{{ $select_id }}" value="{{ $onlysectionId }}">
@endif