<?php

namespace App\Http\Requests\Document;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreDocumentRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'file' => ['required', 'file', 'max:10240'],
            'name' => ['nullable', 'string', 'max:255'],
            'documentable_type' => ['required', 'string', Rule::in([
                'App\Models\JobApplication',
                'App\Models\Company',
                'App\Models\Interview',
                'App\Models\Contact',
            ])],
            'documentable_id' => ['required', 'integer'],
        ];
    }
}
