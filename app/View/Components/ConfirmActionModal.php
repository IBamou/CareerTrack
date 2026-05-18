<?php

namespace App\View\Components;

use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class ConfirmActionModal extends Component
{
    public function __construct(
        public string $name,
        public string $title,
        public string $message,
        public string $action,
        public string $method = 'delete',
        public string $button = 'Confirm',
    ) {}

    public function render(): View
    {
        return view('components.confirm-action-modal');
    }
}
