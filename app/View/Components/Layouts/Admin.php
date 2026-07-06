<?php

namespace App\View\Components\Layouts;

use Illuminate\View\Component;
use Illuminate\Contracts\View\View;

class Admin extends Component
{
    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View
    {
        return view('components.layouts.admin');
    }
}

