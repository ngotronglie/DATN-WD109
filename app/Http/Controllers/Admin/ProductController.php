<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Categories;
use App\Models\Color;
use App\Models\Capacity;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class ProductController extends Controller
{
    private $view;
    private $product;
    private $categories;
    private $colors;
    private $capacities;

    public function __construct(
        Product $product,
        Categories $categories,
        Color $colors,
        Capacity $capacities
    ) {
        $this->product = $product;
        $this->categories = $categories;
        $this->colors = $colors;
        $this->capacities = $capacities;
        $this->view = [];
    }

    public function index()
    {
        $this->view['products'] = $this->product->loadAll();
        return view('layouts.admin.product.list', $this->view);
    }

    public function create()
    {
        $this->view['categories'] = $this->categories->where('Is_active', 1)->get();
        $this->view['colors'] = $this->colors->all();
        $this->view['capacities'] = $this->capacities->all();
        return view('layouts.admin.product.create', $this->view);
    }

    public function store(Request $request)
    {
        try {
            $request->validate([
                'name' => 'required|string|max:255',
                'description' => 'nullable|string',
                'categories_id' => 'required|exists:categories,ID',
                'is_active' => 'required|boolean',
                'variants' => 'required|array|min:1',
                'variants.*.color_id' => 'required|exists:colors,id',
                'variants.*.capacity_id' => 'required|exists:capacities,id',
                'variants.*.price' => 'required|numeric|min:0',
                'variants.*.price_sale' => 'nullable|numeric|min:0',
                'variants.*.quantity' => 'required|integer|min:0',
            ]);

            $data = $request->all();
            $data['slug'] = Str::slug($request->name);
            
            $product = $this->product->insertData($data);

            // Create variants
            foreach ($request->variants as $variant) {
                $product->variants()->create($variant);
            }

            return redirect()->route('admin.products.index')
                ->with('success', 'Sản phẩm đã được thêm thành công!');
        } catch (\Exception $e) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Có lỗi xảy ra: ' . $e->getMessage());
        }
    }

    public function edit($id)
    {
        $this->view['product'] = $this->product->loadOneID($id);
        $this->view['categories'] = $this->categories->where('Is_active', 1)->get();
        $this->view['colors'] = $this->colors->all();
        $this->view['capacities'] = $this->capacities->all();
        return view('layouts.admin.product.update', $this->view);
    }

    public function update(Request $request, $id)
    {
        try {
            $request->validate([
                'name' => 'required|string|max:255',
                'description' => 'nullable|string',
                'categories_id' => 'required|exists:categories,ID',
                'is_active' => 'required|boolean',
                'variants' => 'required|array|min:1',
                'variants.*.id' => 'nullable|exists:product_variants,id',
                'variants.*.color_id' => 'required|exists:colors,id',
                'variants.*.capacity_id' => 'required|exists:capacities,id',
                'variants.*.price' => 'required|numeric|min:0',
                'variants.*.price_sale' => 'nullable|numeric|min:0',
                'variants.*.quantity' => 'required|integer|min:0',
            ]);

            $data = $request->all();
            $data['slug'] = Str::slug($request->name);
            
            $product = $this->product->find($id);
            $product->updateData($data, $id);

            // Update variants
            $product->variants()->delete(); // Delete old variants
            foreach ($request->variants as $variant) {
                $product->variants()->create($variant);
            }

            return redirect()->route('admin.products.index')
                ->with('success', 'Sản phẩm đã được cập nhật thành công!');
        } catch (\Exception $e) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Có lỗi xảy ra: ' . $e->getMessage());
        }
    }

    public function destroy($id)
    {
        try {
            $product = $this->product->find($id);
            $product->variants()->delete(); // Delete all variants
            $product->deleteData($id);

            return redirect()->route('admin.products.index')
                ->with('success', 'Sản phẩm đã được xóa thành công!');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Có lỗi xảy ra: ' . $e->getMessage());
        }
    }
} 