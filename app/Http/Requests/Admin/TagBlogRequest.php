<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class TagBlogRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name_tag' => 'required|string|max:255|unique:tag_blogs,name_tag,' . ($this->tagBlog ? $this->tagBlog->id : ''),
            'content' => 'required|string|min:10|max:1000',
        ];
    }

    public function messages(): array
    {
        return [
            'name_tag.required' => 'Vui lòng nhập tên tag',
            'name_tag.string' => 'Tên tag phải là chuỗi ký tự',
            'name_tag.max' => 'Tên tag không được vượt quá 255 ký tự',
            'name_tag.unique' => 'Tên tag này đã tồn tại',
            'content.required' => 'Vui lòng nhập nội dung',
            'content.string' => 'Nội dung phải là chuỗi ký tự',
            'content.min' => 'Nội dung phải có ít nhất 10 ký tự',
            'content.max' => 'Nội dung không được vượt quá 1000 ký tự',
        ];
    }
} 