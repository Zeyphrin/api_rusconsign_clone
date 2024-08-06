<?php

namespace App\Http\Requests;

use App\Models\Chat;
use Illuminate\Foundation\Http\FormRequest;

class StoreMessageRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules()
    {
        $chatModel = get_class(new Chat());

        return [
            'chat_id' => "required|exists:{$chatModel},id",
            'message' => 'required|string',
        ];
    }
}
