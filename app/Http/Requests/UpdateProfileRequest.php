<?php

namespace App\Http\Requests;

use Auth;
use Illuminate\Foundation\Http\FormRequest;
use App\Repositories\Facades\UserRepository;
use Redirect;

class UpdateProfileRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        //dd($this->request);
        $userData = UserRepository::find($this->route('profile'));
        if ($userData->id == Auth::user()->id) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        if ($this->request->has('password')) {
            return [
                'password' => 'required|min:6|max:32',
                'password_again' => 'required|same:password'
            ];
        }
        return [
            'name' => 'required',
        ];
    }

    /**
 * Get the error messages for the defined validation rules.
 *
 * @ return  array
 */
    public function messages()
    {
        if ($this->request->has('password')) {
            return [
                'password.required' => 'Bạn chưa nhập mật khẩu!',
                'password.min' => 'Mật khẩu gồm tối thiểu 6 ký tự!',
                'password.max' => 'Mật khẩu không được vượt quá 32 ký tự!',
                'password_again.required' => 'Bạn chưa xác nhận mật khẩu!',
                'password_again.same' => 'Mật khẩu xác nhận chưa khớp với mật khẩu đã nhập!'
            ];
        }
        return [
            'name.required' => 'Bạn chưa nhập Tên!',
        ];
    }
}
