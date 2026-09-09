<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class SendBulkSmsRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Prepare the data for validation.
     */
    protected function prepareForValidation(): void
    {
        if (is_string($this->input('recipients'))) {
            $recipients = preg_split('/[\r\n,;]+/', $this->input('recipients'), -1, PREG_SPLIT_NO_EMPTY);
            $this->merge([
                'recipients' => array_map('trim', $recipients ?: []),
            ]);
        }
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'recipients' => ['required', 'array', 'min:1'],
            'recipients.*' => ['required', 'string', 'max:20'],
            'text' => ['required', 'string', 'max:1000'],
            'from' => ['nullable', 'string', 'max:20'],
        ];
    }
}
