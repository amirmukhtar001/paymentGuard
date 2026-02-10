<?php

namespace App\View\Components;

use Illuminate\View\Component;

class SectionDisplay extends Component
{
    public $sections;

    public string $pageType;

    public function __construct(string $pageType = 'home')
    {
        $this->pageType = $pageType;
        $this->sections = collect([]);
    }

    public function render()
    {
        return view('components.section-display');
    }
}
