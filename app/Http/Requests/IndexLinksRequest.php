<?php

namespace App\Http\Requests;

use App\Http\Requests\Trait\ShowRequestTrait;
use Illuminate\Foundation\Http\FormRequest;

class IndexLinksRequest extends FormRequest
{

    use ShowRequestTrait;
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
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {

        $rules = [
            'id' => "integer|nullable",
            'link' => "string|nullable",
            'shortener_link' => "string|nullable",
        ];
        return array_merge($rules, $this->show_rules);
    }
}
