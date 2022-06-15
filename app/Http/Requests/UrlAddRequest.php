<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Validation\Validator;

class UrlAddRequest extends FormRequest
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
        $regex = '/^(https?:\/\/(?:www\.|(?!www))[a-zA-Z0-9][a-zA-Z0-9-]+[a-zA-Z0-9]\.[^\s]{2,}|www\.[a-zA-Z0-9][a-zA-Z0-9-]+[a-zA-Z0-9]\.[^\s]{2,}|https?:\/\/(?:www\.|(?!www))[a-zA-Z0-9]+\.[^\s]{2,}|www\.[a-zA-Z0-9]+\.[^\s]{2,})$/i';

        // since regex has a pipe in it, have to use an array instead of string,
        return [
            'original_url' => ['required', 'string', 'min:9', 'regex:'.$regex],
        ];
    }

    /**
     * Configure the validator instance. (in order to keep return format same)
     *
     * @param Validator $validator
     * @throws HttpResponseException
     */
    public function withValidator(Validator $validator)
    {
        if ($validator->fails()) {
            $errors = $validator->errors();
            $error = [];
            foreach ($errors->messages() as $messages) {
                $error[] = $messages;
            }
            $errors = array_reduce($error, 'array_merge', array());
            throw new HttpResponseException(
                response()->json([
                    'error' => $errors,
                    'status' => 'failed',
                ], 422)
            );
        }
    }
}
