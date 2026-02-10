{{-- resources/views/website_menus/index.blade.php --}}
@extends('layouts.' . config('settings.active_layout'))

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header d-flex justify-content-between">
                <h5 class="card-title">{{ $title }}</h5>
                <a href="{{ route('website-menus.create') }}" class="btn btn-primary">
                    New Menu
                </a>
            </div>
            <div class="card-body">
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>Website</th>
                            <th>Menu Type</th>
                            <th>Title</th>
                            <th>Status</th>
                            <th style="width:160px;">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($menus as $menu)
                        <tr>
                            <td>{{ $menu->website->name ?? '-' }}</td>
                            <td>{{ $menu->type->name ?? '-' }}</td>
                            <td>{{ $menu->title }}</td>
                            <td>{{ ucfirst($menu->status) }}</td>
                            <td>
                                <a href="{{ route('website-menus.edit', $menu->uuid) }}"
                                    class="btn btn-sm btn-info">Edit</a>
                                <form action="{{ route('website-menus.destroy', $menu->uuid) }}"
                                    method="POST" style="display:inline-block">
                                    @csrf
                                    @method('DELETE')
                                    <button class="btn btn-sm btn-danger"
                                        onclick="return confirm('Delete this menu?')">
                                        Delete
                                    </button>
                                </form>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5">No menus found.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection