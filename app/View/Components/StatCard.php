<?php

namespace App\View\Components;

use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class StatCard extends Component
{
    public function __construct(
        public string $title = '',
        public int|string $value = 0,
        public string $icon = '',
        public string $color = 'indigo',
    ) {}

    public function render(): View
    {
        return view('components.stat-card');
    }
}
