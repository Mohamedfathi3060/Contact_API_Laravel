<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class contactRequest extends FormRequest
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
     * @return array<string, mixed>
     */
    public function rules()
    {
        if($this->isMethod('post')){
            return [
                'title' => 'required|unique:contacts',
                'phones'=>'required_without:emails|array',
                'phones.*' => [
                    'regex:/^0[0-9]{10}$/u',
                    'distinct'
                ],
                'emails'=>'required_without:phones|array',
                'emails.*' => [
                    'email',
                    'distinct'
                ],
            ];
        }
        if($this->isMethod('patch') or $this->isMethod('put')){
            return [
                'title' => 'required|unique:contacts,title,'.$this->contact->id,
            ];
        }

        return [

        ];
    }
}
