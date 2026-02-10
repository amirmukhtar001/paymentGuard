<div class="d-flex">
    <a href="{{ $showUrl }}" class="btn btn-sm btn-info mr-1">
        <i class="bx bx-show"></i> Show</a>
    <a href="{{ $editUrl }}" class="btn btn-sm btn-primary mr-1">
        <i class="bx bx-edit"></i> Edit </a>
    <form action="{{ $deleteUrl }}" method="POST" style="display:inline-block" onsubmit="return confirm('Are you sure you want to delete this category?');">
        @csrf
        @method('DELETE')
        <button type="submit" class="btn btn-sm btn-danger">
            <i class="bx bx-trash"></i> Delete </button>
    </form>
</div>