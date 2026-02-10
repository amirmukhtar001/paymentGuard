{{-- resources/views/website_menus/partials/item.blade.php --}}
<li class="list-group-item mb-1 menu-item" data-id="{{ $item->id }}">
    <div class="menu-item-header d-flex justify-content-between align-items-center">
        <span class="item-label-display">{{ $item->label }}</span>
        <div>
            <button type="button" class="btn btn-sm btn-link toggle-item">▲</button>
            <button type="button" class="btn btn-sm btn-link text-danger delete-item">🗑</button>
        </div>
    </div>
    <div class="menu-item-body mt-2">
        <div class="mb-2">
            <label class="form-label">Label</label>
            <input type="text" class="form-control item-label" value="{{ $item->label }}">
        </div>
        <div class="mb-2">
            <label class="form-label">Link</label>
            <input type="text" class="form-control item-url" value="{{ $item->url }}">
        </div>
        <div class="mb-2">
            <label class="form-label">Description</label>
            <textarea class="form-control item-description" rows="2">{{ $item->description }}</textarea>
        </div>
        <div class="mb-2">
            <label class="form-label">Target</label>
            <input type="text" class="form-control item-target"
                value="{{ $item->target ?? '_self' }}" placeholder="_self or _blank">
        </div>

        <ul class="list-group item-children">
            @foreach($item->children as $child)
            @include('website_menus.partials.item', ['item' => $child])
            @endforeach
        </ul>
    </div>
</li>