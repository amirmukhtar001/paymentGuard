@extends('layouts.' . config('settings.active_layout'))

@section('content')
<div class="row">
    <div class="col-12">

        <div class="card">
            <div class="card-header">
                <h5 class="card-title">
                    Search Results for "{{ $keyword }}"
                    <small class="text-muted">in {{ ucfirst($module) }}</small>
                </h5>
            </div>

            <div class="card-body">

                @if($results->isEmpty())
                <div class="alert alert-info">
                    No results found.
                </div>
                @else
                <ul class="list-group">
                    @foreach($results as $item)
                    <li class="list-group-item d-flex justify-content-between">
                        <span>
                            {{ $item->title ?? $item->name ?? 'Item' }}
                        </span>

                        {{-- Module-specific link --}}
                        <a href="{{ url($module . '/' . ($item->slug ?? $item->id)) }}"
                            class="btn btn-sm btn-outline-primary">
                            View
                        </a>
                    </li>
                    @endforeach
                </ul>
                @endif

            </div>
        </div>

    </div>
</div>
@endsection