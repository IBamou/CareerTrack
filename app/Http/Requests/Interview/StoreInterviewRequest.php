<?php

namespace App\Http\Requests\Interview;

use App\Enums\InterviewResult;
use App\Enums\InterviewType;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreInterviewRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'job_application_id' => ['required', 'integer', Rule::exists('job_applications', 'id')->where('applied_by', auth()->id())],
            'type' => ['required', Rule::enum(InterviewType::class)],
            'scheduled_at' => 'required|date',
            'notes' => 'nullable|string',
            'result' => ['nullable', Rule::enum(InterviewResult::class)],
        ];
    }
}
