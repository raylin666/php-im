<?php

declare(strict_types=1);

namespace App\Request\Account;

use Hyperf\Validation\Request\FormRequest;

class AccountRequest extends FormRequest
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
     */
    public function rules(): array
    {
        return [
            'username' => 'required|min:1|max:20',
            'avatar' => 'required|url',
        ];
    }
}
