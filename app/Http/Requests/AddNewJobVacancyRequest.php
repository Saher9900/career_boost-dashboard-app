<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class AddNewJobVacancyRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        if ($this->user()->role === 'admin') {
            return [
                'title' => ['required', 'string', 'max:255'],
                'description' => ['required', 'string'],
                'location' => ['required', 'string', 'max:255'],
                'salary' => ['required', 'string', 'max:255'],
                'type' => ['required', 'in:full_time,contract,remote,hybrid'],
                'company_id' => ['required', 'exists:companies,id'],
                'job_category_id' => ['required', 'exists:job_categories,id'],
            ];
        } else {
            return [
                'title' => ['required', 'string', 'max:255'],
                'description' => ['required', 'string'],
                'salary' => ['required', 'string', 'max:255'],
                'type' => ['required', 'in:full_time,contract,remote,hybrid'],
                'job_category_id' => ['required', 'exists:job_categories,id'],
            ];
        }
    }
}
