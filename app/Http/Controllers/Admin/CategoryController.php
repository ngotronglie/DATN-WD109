<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Categories;
use App\Http\Requests\CategoryRequest;
use Illuminate\Support\Facades\Storage;

class CategoryController extends Controller
{
    private $view;
    private $categories;

    public function __construct(Categories $categories)
    {
        $this->categories = $categories;
        $this->view = [];
    }

    public function index()
    {
        $this->view['categories'] = $this->categories->loadAll();
        return view('layouts.admin.category.list', $this->view);
    }

    public function create()
    {

        $this->view['categories'] = $this->categories->where('Parent_id', null)->get();
        return view('layouts.admin.category.create', $this->view);
    }

    public function store(CategoryRequest $request)
    {
        try {
            $data = $request->validated();

            if ($request->hasFile('Image')) {
                $image = $request->file('Image');
                $imageName = time() . '.' . $image->getClientOriginalExtension();
                $image->storeAs('public/categories', $imageName);
                $data['Image'] = 'storage/categories/' . $imageName;
            }


            $result = $this->categories->insertData($data);

            if ($result) {
                return redirect()->route('admin.categories.index')->with('success', 'Thêm danh mục thành công');
            } else {
                return redirect()->back()->withInput()->with('error', 'Thêm danh mục thất bại');
            }
        } catch (\Exception $e) {
            return redirect()->back()->withInput()->with('error', 'Có lỗi xảy ra: ' . $e->getMessage());
        }
    }

    public function edit($id)
    {
        $this->view['category'] = $this->categories->loadOneID($id);
        $this->view['categories'] = $this->categories->where('Parent_id', null)->get();
        return view('layouts.admin.category.update', $this->view);
    }

    public function update(CategoryRequest $request, $id)
    {
        try {
            $data = $request->validated();

            if ($request->hasFile('Image')) {
                $oldCategory = $this->categories->find($id);
                if ($oldCategory && $oldCategory->Image) {
                    Storage::delete('public/' . str_replace('storage/', '', $oldCategory->Image));
                }

                $image = $request->file('Image');
                $imageName = time() . '.' . $image->getClientOriginalExtension();
                $image->storeAs('public/categories', $imageName);
                $data['Image'] = 'storage/categories/' . $imageName;
            }

            $result = $this->categories->updateData($data, $id);

            if ($result) {
                return redirect()->route('admin.categories.index')->with('success', 'Cập nhật danh mục thành công');
            } else {
                return redirect()->back()->withInput()->with('error', 'Cập nhật danh mục thất bại');
            }
        } catch (\Exception $e) {
            return redirect()->back()->withInput()->with('error', 'Có lỗi xảy ra: ' . $e->getMessage());
        }
    }

    public function destroy($id)
    {
        try {
            $category = $this->categories->find($id);
            if ($category && $category->Image) {
                Storage::delete('public/' . str_replace('storage/', '', $category->Image));
            }

            $result = $this->categories->deleteData($id);

            if ($result) {
                return redirect()->route('admin.categories.index')->with('success', 'Xóa danh mục thành công');
            } else {
                return redirect()->back()->with('error', 'Xóa danh mục thất bại');
            }
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Có lỗi xảy ra: ' . $e->getMessage());
        }
    }
}
