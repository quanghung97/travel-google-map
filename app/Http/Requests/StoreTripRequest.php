<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Redirect;

class StoreTripRequest extends FormRequest
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
            'name' => 'required',
            'leave_time0' => 'required',
            'arrival_time0' => 'required' 
        ];
    }

    /**
    * Get the error messages for the defined validation rules.
    *
    *  @ return  array
    */
    public function messages()
    {
        return [
            'name.required' => 'Bạn chưa nhập Tên chuyến đi!',
            'leave_time0.required' => 'Bạn chưa nhập thời gian bắt đầu',
            'arrival_time0.required' => 'Bạn chưa nhập thời gian kết thúc'
        ];
    }
}
