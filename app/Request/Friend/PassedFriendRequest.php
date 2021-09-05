<?php

declare(strict_types=1);

namespace App\Request\Friend;

use Hyperf\Validation\Request\FormRequest;

class PassedFriendRequest extends FormRequest
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
            'from_account_id' => 'required|numeric',
            'remark' => 'string|max:20',
        ];
    }
}