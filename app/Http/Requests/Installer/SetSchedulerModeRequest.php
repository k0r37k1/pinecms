<?php

declare(strict_types=1);

namespace App\Http\Requests\Installer;

use Illuminate\Foundation\Http\FormRequest;

class SetSchedulerModeRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true; // No authorization required for installer
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, array<int, string>>
     */
    public function rules(): array
    {
        return [
            'mode' => ['required', 'string', 'in:visit-triggered,cron'],
        ];
    }
}
