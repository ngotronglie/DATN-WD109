<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Categories;
use App\Models\Color;
use App\Models\Capacity;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;

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
        $products = DB::table('product_variants')
            ->join('products', 'product_variants.product_id', '=', 'products.id')
            ->join('colors', 'product_variants.color_id', '=', 'colors.id')
            ->join('categories', 'products.categories_id', '=', 'categories.id')
            ->join('capacities', 'product_variants.capacity_id', '=', 'capacities.id')
            ->select([
                'product_variants.id',
                'product_variants.price',
                'product_variants.price_sale',
                'product_variants.quantity',
                'products.name as product_name',
                'products.is_active',
                'products.view_count',
                'colors.name as color_name',
                'capacities.name as capacity_name',
                'categories.Name as category_name',
            ])
            ->paginate(5); // <-- 10 dòng mỗi trang

        return view('layouts.admin.product.list', compact('products'));
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
