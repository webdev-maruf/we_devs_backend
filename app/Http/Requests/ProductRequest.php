<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ProductRequest extends FormRequest
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
        $sometimesRequired =  $this->product?'nullable':'required';

        return [
            'title' => [
                'required',
                'min:5',
                'regex:/^[a-zA-Z- _0-9]+$/',
                Rule::unique('products','title')
                ->ignore($this->product)
            ],
            'description' => 'required',
            'price' => 'required|numeric',
            'image' => [$sometimesRequired,'mimes:jpg,png,gif','max:1024'],
            
        ]; 
    }

    /**
    * Get custom attributes for validator errors.
    *
    * @return array
    */
    public function attributes()
    {
        return [
            'title' => 'Title',
            'image'=>'Product Image',
            'price' => 'Price',
            'description' => 'Description'
        ];
    }

    /**
    * Get the error messages for the defined validation rules.
    *
    * @return array
    */
    public function messages()
    {
        return [
            'title.required' => ':attribute is required',
            'title.min' => ':attribute atleast 5 character',
            'title.regex' => ':attribute must be alpha numeric',
            'title.unique' => ':attribute can not be duplicate',
            'description.required'=> ':attribute is required',
            'price.required'=> ':attribute is required',
            'price.numeric'=> ':attribute must be number',
            'image.required' => ':attribute is required',
            'image.mimes' => 'Invalid :attribute type',
            'image.max'=>':attribute can be maximum 1mb'
        ];
    }
}
