<?php

namespace App\Http\Requests\Reminder;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateReminderRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'title' => 'sometimes|required|string|max:255',
            'description' => 'nullable|string',
            'remind_at' => 'sometimes|required|date',
            'remindable_type' => ['nullable', 'string', Rule::in(['App\Models\Interview', 'App\Models\JobApplication'])],
            'remindable_id' => ['nullable', 'integer', 'required_with:remindable_type'],
        ];
    }
}
