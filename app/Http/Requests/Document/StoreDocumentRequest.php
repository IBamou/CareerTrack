<?php

namespace App\Http\Requests\Document;

use App\Models\Company;
use App\Models\Contact;
use App\Models\Interview;
use App\Models\JobApplication;
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
            'file' => ['required', 'file', 'mimes:pdf,doc,docx', 'max:10240'],
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

    public function withValidator($validator)
    {
        $validator->after(function ($validator) {
            $type = $this->input('documentable_type');
            $id = $this->input('documentable_id');
            $userId = auth()->id();

            $entity = match ($type) {
                'App\Models\JobApplication' => JobApplication::find($id),
                'App\Models\Company' => Company::find($id),
                'App\Models\Interview' => Interview::find($id),
                'App\Models\Contact' => Contact::find($id),
                default => null,
            };

            if (! $entity) {
                $validator->errors()->add('documentable_id', 'The related entity was not found.');

                return;
            }

            $ownerId = match ($type) {
                'App\Models\JobApplication' => $entity->applied_by,
                'App\Models\Company' => $entity->user_id,
                'App\Models\Interview' => $entity->user_id,
                'App\Models\Contact' => $entity->user_id,
                default => null,
            };

            if ($ownerId !== $userId) {
                $validator->errors()->add('documentable_id', 'You do not own this entity.');
            }
        });
    }
}
