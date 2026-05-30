<?php

namespace App\Http\Requests\Reminder;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreReminderRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'remind_at' => 'required|date',
            'remindable_type' => ['nullable', 'string', Rule::in(['App\Models\Interview', 'App\Models\JobApplication'])],
            'remindable_id' => ['nullable', 'integer', 'required_with:remindable_type'],
        ];
    }
}
