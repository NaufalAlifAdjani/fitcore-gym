<?php

namespace App\View\Components;

use Illuminate\View\Component;
use Illuminate\View\View;

class PublicLayout extends Component
{
    /**
     * Create a new component instance.
     */
    public function __construct(
        public string $title = 'FitCore Athletic - Gym & Performance Center',
        public string $activeTab = 'home',
    ) {}

    /**
     * Get the view / contents that represents the component.
     */
    public function render(): View
    {
        return view('layouts.public');
    }
}
