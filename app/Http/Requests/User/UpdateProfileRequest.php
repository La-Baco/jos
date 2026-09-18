<?php

namespace App\Http\Requests\User;

use Illuminate\Foundation\Http\FormRequest;

class UpdateProfileRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->check() && auth()->user()->isJobSeeker();
    }

    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'phone' => ['nullable', 'string', 'max:25'],
            'birth_date' => ['nullable', 'date', 'before:today'],
            'gender' => ['nullable', 'in:L,P'],
            'province' => ['nullable', 'string', 'max:100'],
            'regency' => ['required', 'string', 'max:100'],
            'district' => ['nullable', 'string', 'max:100'],
            'village' => ['nullable', 'string', 'max:100'],
            'education_level_id' => ['nullable', 'exists:education_levels,id'],
            'major' => ['nullable', 'string', 'max:150'],
            'graduation_year' => ['nullable', 'integer', 'min:1970', 'max:'.(date('Y') + 5)],
            'willing_outside_sumenep' => ['boolean'],
            'willing_outside_madura' => ['boolean'],
            'willing_remote' => ['boolean'],
            'expected_salary_min' => ['nullable', 'numeric', 'min:0'],
            'preferred_location' => ['nullable', 'string', 'max:150'],
            'bio' => ['nullable', 'string', 'max:1000'],
            'preferred_categories' => ['nullable', 'array'],
            'preferred_categories.*' => ['exists:job_categories,id'],
        ];
    }
}
