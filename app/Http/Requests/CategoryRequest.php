<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\Models\Categories;

class CategoryRequest extends FormRequest
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
        $rules = [
            'Name' => ['required', 'string', 'max:255'],
            'Parent_id' => ['nullable', 'exists:categories,ID'],
            'Is_active' => ['required', 'in:0,1'],
        ];

     
        if ($this->isMethod('PUT')) {
            $category = Categories::find($this->route('id'));
            
          
            $hasChanges = false;
            if ($category) {
                $hasChanges = $this->input('Name') !== $category->Name ||
                             $this->input('Parent_id') != $category->Parent_id ||
                             $this->input('Is_active') != $category->Is_active ||
                             $this->hasFile('Image');
            }

            if (!$hasChanges) {
                $rules['no_changes'] = ['required'];
            }

       
            if ($this->hasFile('Image')) {
                // Avoid relying on fileinfo; validate by extension/mime only
                $rules['Image'] = ['required', 'mimes:jpeg,png,jpg,gif,webp', 'max:4096'];
            }
        } else {
            // Create: allow optional image
            $rules['Image'] = ['nullable', 'mimes:jpeg,png,jpg,gif,webp', 'max:4096'];
        }

        return $rules;
    }

    /**
     * Get custom messages for validator errors.
     *
     * @return array
     */
    public function messages(): array
    {
        return [
            'Name.required' => 'Vui lòng nhập tên danh mục',
            'Name.string' => 'Tên danh mục phải là chuỗi ký tự',
            'Name.max' => 'Tên danh mục không được vượt quá 255 ký tự',
            'Parent_id.exists' => 'Danh mục cha không tồn tại',
            'Is_active.required' => 'Vui lòng chọn trạng thái',
            'Is_active.in' => 'Trạng thái không hợp lệ',
            'Image.required' => 'Vui lòng chọn hình ảnh',
            'Image.image' => 'File phải là hình ảnh',
            'Image.mimes' => 'Hình ảnh phải có định dạng: jpeg, png, jpg, gif',
            'Image.max' => 'Kích thước hình ảnh không được vượt quá 2MB',
            'no_changes.required' => 'Bạn chưa thay đổi thông tin nào',
        ];
    }

    /**
     * Prepare the data for validation.
     *
     * @return void
     */
    protected function prepareForValidation()
    {
        
        if ($this->has('Is_active')) {
            $this->merge([
                'Is_active' => (int) $this->input('Is_active'),
            ]);
        }

       
        if ($this->has('Name')) {
            $this->merge([
                'Name' => trim($this->input('Name')),
            ]);
        }
    }
} 