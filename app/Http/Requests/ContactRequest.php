<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ContactRequest extends FormRequest
{
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
        return [
            'con_name' => 'required|string|max:255',
            'con_email' => 'required|email|max:255',
            'con_subject' => 'required|string|max:255',
            'con_phone' => 'required|string|max:20',
            'con_message' => 'required|string|max:1000',
            'user_id' => 'nullable|exists:users,id',
        ];
    }

    /**
     * Get custom messages for validator errors.
     *
     * @return array
     */
    public function messages(): array
    {
        return [
            'con_name.required' => 'Vui lòng nhập họ và tên.',
            'con_name.string' => 'Họ và tên phải là chuỗi ký tự.',
            'con_name.max' => 'Họ và tên không được vượt quá 255 ký tự.',
            
            'con_email.required' => 'Vui lòng nhập email.',
            'con_email.email' => 'Email không đúng định dạng.',
            'con_email.max' => 'Email không được vượt quá 255 ký tự.',
            
            'con_subject.required' => 'Vui lòng nhập tiêu đề.',
            'con_subject.string' => 'Tiêu đề phải là chuỗi ký tự.',
            'con_subject.max' => 'Tiêu đề không được vượt quá 255 ký tự.',
            
            'con_phone.required' => 'Vui lòng nhập số điện thoại.',
            'con_phone.string' => 'Số điện thoại phải là chuỗi ký tự.',
            'con_phone.max' => 'Số điện thoại không được vượt quá 20 ký tự.',
            
            'con_message.required' => 'Vui lòng nhập nội dung tin nhắn.',
            'con_message.string' => 'Nội dung tin nhắn phải là chuỗi ký tự.',
            'con_message.max' => 'Nội dung tin nhắn không được vượt quá 1000 ký tự.',
        ];
    }
}
