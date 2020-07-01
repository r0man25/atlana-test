<?php

namespace App\Http\Requests;

use App\Image;
use App\Rules\Base64;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

/**
 * Class ProcessRequest
 * @package App\Http\Requests
 */
class ProcessRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'image' => ['required', new Base64()],
            'type' => ['required', Rule::in(Image::imageTypes())]
        ];
    }
}
