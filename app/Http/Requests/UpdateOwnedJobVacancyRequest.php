<?php

namespace App\Http\Requests;

use App\Models\JobVacancy;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Gate;
use Illuminate\Validation\Rule;

class UpdateOwnedJobVacancyRequest extends FormRequest
{
    public function authorize(): bool
    {
        $jobVacancy = $this->route('jobVacancy');

        return $jobVacancy instanceof JobVacancy
            && Gate::allows('updateVacancyByOwner', $jobVacancy);
    }

    /**
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'title' => ['required', 'string', 'max:255'],
            'description' => ['required', 'string'],
            'location' => ['required', 'string', 'max:255'],
            'salary' => ['required', 'string', 'max:255'],
            'type' => ['required', 'in:full_time,contract,remote,hybrid'],
            'company_id' => [
                'required',
                Rule::exists('companies', 'id')->where('owner_id', $this->user()->id),
            ],
            'job_category_id' => ['required', 'exists:job_categories,id'],
        ];
    }
}
