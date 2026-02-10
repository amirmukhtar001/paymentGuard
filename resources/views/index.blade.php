@extends('layouts.' . config('settings.active_layout'))

@push('scripts')
<script>
    $(document).ready(function() {

        /* -------------------------------
         | Enable / Disable Search Button
         |-------------------------------*/
        function toggleSearchButton() {
            const hasKeyword = $('#global-search').val().trim().length >= 2;
            const hasModule = $('#search-module').val() !== '';
            $('#search-btn').prop('disabled', !(hasKeyword && hasModule));
        }

        $('#search-module').on('change', function() {
            const enabled = !!this.value;
            $('#global-search').prop('disabled', !enabled).val('');
            $('#search-results').addClass('d-none').html('');
            toggleSearchButton();
        });

        /* -------------------------------
         | AUTOCOMPLETE SEARCH
         |-------------------------------*/
        let searchTimer = null;

        $('#global-search').on('keyup', function() {
            const keyword = $(this).val().trim();
            const module = $('#search-module').val();

            toggleSearchButton();
            clearTimeout(searchTimer);

            if (!module || keyword.length < 2) {
                $('#search-results').addClass('d-none').html('');
                return;
            }

            searchTimer = setTimeout(function() {
                $.ajax({
                    url: "{{ route('frontend.search') }}",
                    type: "GET",
                    data: {
                        keyword: keyword,
                        module: module
                    },
                    success: function(response) {
                        renderResults(response.data);
                    },
                    error: function() {
                        $('#search-results')
                            .removeClass('d-none')
                            .html('<div class="list-group-item text-danger">Search failed</div>');
                    }
                });
            }, 300);
        });

        /* -------------------------------
         | Render autocomplete list
         |-------------------------------*/
        function renderResults(items) {
            let html = '';

            if (!items || items.length === 0) {
                html = '<div class="list-group-item text-muted">No results found</div>';
            } else {
                $.each(items, function(i, item) {
                    const label = item.title || item.name || item.email || 'View';

                    html += `
                    <a href="javascript:void(0)"
                       class="list-group-item list-group-item-action autocomplete-item"
                       data-slug="${item.slug ?? ''}"
                       data-id="${item.id ?? ''}">
                        ${label}
                    </a>
                `;
                });
            }

            $('#search-results')
                .removeClass('d-none')
                .html(html);
        }

        /* -------------------------------
         | Autocomplete click (fill only)
         |-------------------------------*/
        $(document).on('click', '.autocomplete-item', function() {
            $('#global-search').val($(this).text().trim());
            $('#search-results').addClass('d-none');
            toggleSearchButton();
        });

        /* -------------------------------
         | Search Button → Results Page
         |-------------------------------*/
        $('#search-btn').on('click', function() {
            const keyword = $('#global-search').val().trim();
            const module = $('#search-module').val();

            if (!keyword || !module) return;

            const url =
                "{{ route('frontend.search.results') }}" +
                "?keyword=" + encodeURIComponent(keyword) +
                "&module=" + encodeURIComponent(module);

            window.location.href = url;
        });

        /* -------------------------------
         | Hide dropdown on outside click
         |-------------------------------*/
        $(document).on('mousedown', function(e) {
            if (!$(e.target).closest('#global-search, #search-results').length) {
                $('#search-results').addClass('d-none');
            }
        });

    });
</script>
@endpush




@section('content')

<div class="row">
    <div class="col-12">

        {{-- 🔍 MODULE SEARCH WITH AUTOCOMPLETE --}}
        <div class="card mb-3">
            <div class="card-body">
                <div class="row">

                    <div class="col-md-4">
                        <label class="form-label">Search Module</label>
                        <select id="search-module" class="form-control">
                            <option value="">Select Module</option>
                            <option value="pages">Pages</option>
                            <option value="posts">Posts</option>
                            <option value="users">Users</option>
                        </select>
                    </div>

                    <div class="col-md-8 position-relative">
                        <label class="form-label">Search</label>

                        <div class="input-group">
                            <input type="text"
                                id="global-search"
                                class="form-control"
                                placeholder="Start typing to search..."
                                autocomplete="off"
                                disabled>

                            <button class="btn btn-primary"
                                id="search-btn"
                                type="button"
                                disabled>
                                Search
                            </button>
                        </div>

                        {{-- Autocomplete --}}
                        <div id="search-results"
                            class="list-group position-absolute w-100 d-none"
                            style="z-index: 1000; max-height: 250px; overflow-y: auto;"></div>
                    </div>

                </div>
            </div>
        </div>



    </div>
</div>

</div>
</div>

@endsection