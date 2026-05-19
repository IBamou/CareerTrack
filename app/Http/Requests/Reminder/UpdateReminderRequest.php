<?php

namespace App\Http\Requests\Reminder;

use Illuminate\Foundation\Http\FormRequest;

class UpdateReminderRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'title' => ['sometimes', 'required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'remind_at' => ['sometimes', 'required', 'date'],
            'status' => ['sometimes', 'required', 'in:pending,sent,cancelled'],
        ];
    }
}
