<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ContactRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'con_name' => 'required|string|max:255',
            'con_email' => 'required|email',
            'con_subject' => 'required|string|max:255',
            'con_phone' => 'required|digits_between:9,12',
            'con_message' => 'required|string|min:10',
        ];
    }

    public function messages()
    {
        return [
            'con_name.required' => 'Vui lòng nhập họ tên.',
            'con_email.required' => 'Vui lòng nhập email.',
            'con_email.email' => 'Email không hợp lệ.',
            'con_subject.required' => 'Vui lòng nhập tiêu đề.',
            'con_phone.required' => 'Vui lòng nhập số điện thoại.',
            'con_phone.digits_between' => 'Số điện thoại phải từ 9 đến 12 số.',
            'con_message.required' => 'Vui lòng nhập nội dung.',
            'con_message.min' => 'Nội dung phải ít nhất 10 ký tự.',
        ];
    }
} 