<?php

declare(strict_types=1);

namespace App\Site\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UserRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            // 'roles' => 'required',
        ];
    }
}
