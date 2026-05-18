<?php

namespace App\View\Components;

use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class EmptyState extends Component
{
    public function __construct(
        public string $title,
        public string $message,
        public ?string $action = null,
        public ?string $route = null,
    ) {}

    public function render(): View
    {
        return view('components.empty-state');
    }
}
