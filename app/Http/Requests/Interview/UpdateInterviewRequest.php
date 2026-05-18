<?php

namespace App\Http\Requests\Interview;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateInterviewRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'job_application_id' => ['sometimes', 'required', 'integer', Rule::exists('job_applications', 'id')->where('applied_by', auth()->id())],
            'type' => 'sometimes|required|string|max:255',
            'scheduled_at' => 'sometimes|required|date',
            'notes' => 'nullable|string',
            'result' => 'nullable|string|max:255',
        ];
    }
}
