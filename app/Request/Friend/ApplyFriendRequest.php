<?php

declare(strict_types=1);

namespace App\Request\Friend;

use Hyperf\Validation\Request\FormRequest;

class ApplyFriendRequest extends FormRequest
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
            'to_account_id' => 'required|numeric',
            'remark' => 'string|max:20',
        ];
    }
}
