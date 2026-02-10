@foreach($sections as $section)
    <section class="page-section section-{{ $section->section_key }}" data-section-id="{{ $section->id }}">

        @if($section->title)
            <div class="container">
                <h2 class="section-title">{{ $section->title }}</h2>
                @if($section->description)
                    <p class="section-description">{{ $section->description }}</p>
                @endif
            </div>
        @endif

        <div class="container">
            @include('sections.layouts.' . $section->layout_type, [
                'items' => $section->items,
                'settings' => $section->settings
            ])
                </div>
            </section>
@endforeach