<?php

namespace App\Http\Requests\JobApplication;

use App\Enums\JobApplicationStatus;
use App\Enums\JobLocationType;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Enum;

class UpdateJobApplicationRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'job_title' => 'sometimes|required|string|max:255',
            'company_id' => ['nullable', 'integer', Rule::exists('companies', 'id')->where('user_id', auth()->id())],
            'new_company_name' => 'nullable|string|max:255',
            'status' => ['sometimes', 'required', new Enum(JobApplicationStatus::class)],
            'priority' => 'sometimes|required|string|in:low,normal,high',
            'location_type' => ['sometimes', 'required', new Enum(JobLocationType::class)],
            'location_city' => 'nullable|string|max:255',
            'applied_at' => 'nullable|date',

            'links' => 'nullable|array',
            'links.*.label' => 'required|string|max:255',
            'links.*.url' => 'required|url:http,https',
            'notes' => 'nullable|string',
            'salary_min' => ['sometimes', 'nullable', 'numeric', 'min:0'],
            'salary_max' => ['sometimes', 'nullable', 'numeric', 'min:0'],
            'currency' => ['sometimes', 'nullable', 'string', 'max:3'],
            'benefits' => ['sometimes', 'nullable', 'string'],
            'tags' => ['sometimes', 'nullable', 'array'],
            'tags.*' => [Rule::exists('tags', 'id')->where('user_id', auth()->id())],
        ];
    }

    public function withValidator($validator)
    {
        $validator->after(function ($validator) {
            $companyId = $this->input('company_id');
            $newName = $this->input('new_company_name');
            $existingCompanyId = $this->route('jobApplication')?->company_id;

            if (blank($companyId) && blank($newName) && blank($existingCompanyId)) {
                $validator->errors()->add('company_id', 'Please select a company or enter a new company name.');
            }

            if (filled($companyId) && filled($newName)) {
                $validator->errors()->add('new_company_name', 'Cannot select an existing company and enter a new company name at the same time.');
            }
        });
    }
}
