<?php

namespace App\View\Components;

use Illuminate\View\Component;
use Illuminate\View\View;

class FitcoreLayout extends Component
{
    public function __construct(
        public ?string $title = 'FITCORE ATHLETIC - Personal Trainer'
    ) {}

    /**
     * Get the view / contents that represents the component.
     */
    public function render(): View
    {
        return view('layouts.fitcore');
    }
}
