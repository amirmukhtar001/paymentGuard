@extends('layouts.' . config('settings.active_layout'))

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header header-elements-inline">
                    <h5 class="card-title">{{ $title }}</h5>
                </div>
                <div class="card-body">
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <h6>Basic Information</h6>
                            <p><strong>Title:</strong> {{ $item->title }}</p>
                            <p><strong>Website:</strong> {{ $item->company->title ?? '-' }}</p>
                            <p><strong>Category:</strong> {{ $item->category->title ?? '-' }}</p>
                            <p><strong>Status:</strong> {{ ucfirst($item->status) }}</p>
                            <p><strong>Poll Type:</strong>
                                {{ $item->poll_type === 'multiple_choice' ? 'Multiple Choice' : 'Single Choice' }}</p>
                            <p><strong>Start Date:</strong> {{ optional($item->start_date)->format('Y-m-d H:i') ?? '-' }}</p>
                            <p><strong>End Date:</strong> {{ optional($item->end_date)->format('Y-m-d H:i') ?? '-' }}</p>
                        </div>
                        <div class="col-md-6">
                            <h6>Settings</h6>
                            {{-- Flags temporarily hidden
                            <p><strong>Allow Anonymous:</strong> {{ $item->allow_anonymous ? 'Yes' : 'No' }}</p>
                            <p><strong>Allow Multiple Votes:</strong> {{ $item->allow_multiple_votes ? 'Yes' : 'No' }}</p>
                            <p><strong>Show Results Immediately:</strong> {{ $item->show_results_immediately ? 'Yes' : 'No' }}</p>
                            <p><strong>Show Results Before Voting:</strong> {{ $item->show_results_before_voting ? 'Yes' : 'No' }}</p>
                            <p><strong>Show Results After Close:</strong> {{ $item->show_results_after_close ? 'Yes' : 'No' }}</p>
                            <p><strong>Randomize Options:</strong> {{ $item->randomize_options ? 'Yes' : 'No' }}</p>
                            --}}
                            <p><strong>Total Votes:</strong> {{ $item->total_votes }}</p>
                            <p><strong>Views:</strong> {{ $item->views }}</p>
                        </div>
                    </div>

                    @if ($item->thumbnail)
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <h6>Thumbnail</h6>
                                <img src="{{ $item->thumbnail->file_url }}" alt="Thumbnail" class="img-thumbnail"
                                    style="max-height: 180px;">
                            </div>
                        </div>
                    @endif

                    @if ($item->description)
                        <div class="row mb-3">
                            <div class="col-md-12">
                                <h6>Description</h6>
                                <div>{!! $item->description !!}</div>
                            </div>
                        </div>
                    @endif

                    <div class="row mb-3">
                        <div class="col-md-12">
                            <h6>Options</h6>
                            <div class="table-responsive">
                                <table class="table table-bordered">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>Text</th>
                                            <th>Image</th>
                                            <th>Display Order</th>
                                            <th>Vote Count</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($item->options->sortBy('display_order') as $index => $option)
                                            <tr>
                                                <td>{{ $index + 1 }}</td>
                                                <td>{{ $option->option_text }}</td>
                                                <td>
                                                    @if ($option->image)
                                                        @php
                                                            $url = $option->image->file_path ? asset('storage/' . $option->image->file_path) : ($option->image->external_url ?? '');
                                                        @endphp
                                                        @if($url)
                                                            <img src="{{ $url }}" alt="Option Image"
                                                                class="img-thumbnail" style="max-height: 80px; max-width: 80px; object-fit: cover;">
                                                        @else
                                                            -
                                                        @endif
                                                    @else
                                                        -
                                                    @endif
                                                </td>
                                                <td>{{ $option->display_order }}</td>
                                                <td>{{ $option->vote_count }}</td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>

                    @if ($item->meta_key || $item->meta_value)
                        <div class="row mb-3">
                            <div class="col-md-12">
                                <h6>Meta Information</h6>
                                <p><strong>Meta Key:</strong> {{ $item->meta_key ?? '-' }}</p>
                                <p><strong>Meta Value:</strong> {{ $item->meta_value ?? '-' }}</p>
                            </div>
                        </div>
                    @endif

                    <div class="mt-3">
                        <a href="{{ route('settings.polls.list') }}" class="btn btn-secondary">
                            <i class="bx bx-arrow-back"></i> Back
                        </a>
                        <a href="{{ route('settings.polls.edit', $item->uuid) }}" class="btn btn-primary">
                            <i class="bx bx-edit"></i> Edit
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
