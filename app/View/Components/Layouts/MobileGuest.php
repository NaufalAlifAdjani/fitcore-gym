<?php

namespace App\View\Components\Layouts;

use Illuminate\View\Component;
use Illuminate\View\View;

class MobileGuest extends Component
{
    /**
     * Create a new component instance.
     */
    public function __construct(
        public string $title = 'FitCore Athletic - Gym & Performance Center',
        public string $activeTab = 'home',
        public bool $hideNav = false,
    ) {}

    /**
     * Get the view / contents that represents the component.
     */
    public function render(): View
    {
        return view('layouts.mobile-guest');
    }
}
