<?php

namespace App\Http\Requests\User;

use Illuminate\Foundation\Http\FormRequest;

class StoreExperienceRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->check() && auth()->user()->isJobSeeker();
    }

    public function rules(): array
    {
        return [
            'job_title' => ['required', 'string', 'max:150'],
            'company_name' => ['required', 'string', 'max:150'],
            'start_year' => ['required', 'integer', 'min:1970', 'max:'.date('Y')],
            'end_year' => ['nullable', 'integer', 'gte:start_year', 'max:'.(date('Y') + 1)],
            'is_current' => ['boolean'],
            'description' => ['nullable', 'string', 'max:1000'],
        ];
    }
}
