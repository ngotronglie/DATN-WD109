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
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use App\Models\ImageVariant;
use App\Models\ProductVariant;

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
            ->leftJoin('image_variants', 'product_variants.id', '=', 'image_variants.variant_id')
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
                DB::raw('GROUP_CONCAT(image_variants.image) as images')
            ])
            ->groupBy('product_variants.id', 'product_variants.price', 'product_variants.price_sale',
                     'product_variants.quantity', 'products.name', 'products.is_active',
                     'products.view_count', 'colors.name', 'capacities.name', 'categories.Name')
            ->paginate(10);

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
            DB::beginTransaction();

            // Validate dữ liệu cơ bản của sản phẩm
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

            // Tạo sản phẩm mới
            $product = new Product();
            $product->name = $request->name;
            $product->description = $request->description;
            $product->slug = Str::slug($request->name);
            $product->is_active = $request->is_active;
            $product->categories_id = $request->categories_id;
            $product->view_count = 0;
            $product->save();

            // Tạo các biến thể sản phẩm
            foreach ($request->variants as $index => $variantData) {
                // Tạo biến thể
                $variant = new ProductVariant();
                $variant->product_id = $product->id;
                $variant->color_id = $variantData['color_id'];
                $variant->capacity_id = $variantData['capacity_id'];
                $variant->price = $variantData['price'];
                $variant->price_sale = $variantData['price_sale'] ?? null;
                $variant->quantity = $variantData['quantity'];
                $variant->save();

                // Xử lý upload ảnh cho biến thể
                if ($request->hasFile("variants.{$index}.images")) {
                    $images = $request->file("variants.{$index}.images");

                    // Nếu chỉ có một ảnh, chuyển thành mảng
                    if (!is_array($images)) {
                        $images = [$images];
                    }

                    foreach ($images as $image) {
                        if ($image && $image->isValid()) {
                            // Tạo tên file duy nhất
                            $extension = $image->getClientOriginalExtension();
                            $fileName = 'variant_' . $variant->id . '_' . time() . '_' . uniqid() . '.' . $extension;

                            // Lưu ảnh vào storage
                            $path = $image->storeAs('products/variants', $fileName, 'public');

                            if ($path) {
                                // Tạo bản ghi ảnh trong database
                                $imageVariant = new ImageVariant();
                                $imageVariant->variant_id = $variant->id;
                                $imageVariant->image = $path;
                                $imageVariant->save();
                            }
                        }
                    }
                }
            }

            DB::commit();

            return redirect()->route('admin.product.index')
                ->with('success', 'Sản phẩm đã được thêm thành công!');
        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error('Lỗi khi tạo sản phẩm: ' . $e->getMessage());
            return redirect()->back()
                ->withInput()
                ->with('error', 'Có lỗi xảy ra: ' . $e->getMessage());
        }
    }

    public function edit($id)
    {
        $product = $this->product->loadOneID($id);
        if (!$product) {
            return redirect()->route('admin.product.index')
                ->with('error', 'Không tìm thấy sản phẩm để chỉnh sửa!');
        }
        $this->view['product'] = $product;
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

            return redirect()->route('admin.product.index')
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
            if ($product) {
                // Xóa tất cả các biến thể và ảnh liên quan trước khi xóa sản phẩm
                foreach ($product->variants as $variant) {
                    foreach ($variant->images as $image) {
                        Storage::disk('public')->delete($image->image); // Xóa file ảnh vật lý
                        $image->delete(); // Xóa bản ghi ảnh
                    }
                    $variant->delete(); // Xóa bản ghi biến thể
                }
                $product->deleteData($id); // Xóa sản phẩm
            }
            return redirect()->route('admin.product.index')
                ->with('success', 'Sản phẩm đã được xóa thành công!');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Có lỗi xảy ra: ' . $e->getMessage());
        }
    }

    // Phương thức để hiển thị trang quản lý ảnh cho một sản phẩm (chứa các biến thể)
    public function addfiledetail($id)
    {
        $product = $this->product->loadOneID($id);
        if (!$product) {
            return redirect()->route('admin.product.index')
                ->with('error', 'Không tìm thấy sản phẩm!');
        }
        return view('layouts.admin.product.addfiledetail', compact('product'));
    }

    // Phương thức để xử lý việc cập nhật/thêm ảnh cho các biến thể
    public function updateImages(Request $request, $id)
    {
        try {
            $request->validate([
                'variants.*.images.*' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048'
            ]);

            $product = $this->product->loadOneID($id);
            if (!$product) {
                return redirect()->back()->with('error', 'Không tìm thấy sản phẩm!');
            }

            if ($request->hasFile('variants')) {
                foreach ($request->file('variants') as $variantId => $variantData) {
                    if (!isset($variantData['images'])) {
                        continue;
                    }

                    $variant = $product->variants()->find($variantId);
                    if (!$variant) {
                        continue;
                    }

                    // Xử lý nhiều ảnh cho mỗi biến thể
                    if (is_array($variantData['images'])) {
                        foreach ($variantData['images'] as $image) {
                            if ($image->isValid()) {
                                $path = $image->store('products/variants', 'public');

                                // Tạo bản ghi mới trong bảng image_variants
                                $variant->images()->create([
                                    'image' => $path,
                                    'variant_id' => $variantId
                                ]);
                            }
                        }
                    }
                }
            }

            return redirect()->back()->with('success', 'Cập nhật ảnh thành công!');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Có lỗi xảy ra: ' . $e->getMessage());
        }
    }

    // Phương thức để xử lý việc xóa ảnh của biến thể
    public function deleteImage($variantId, $imageId)
    {
        try {
            $image = ImageVariant::findOrFail($imageId);
            Storage::disk('public')->delete($image->image); // Đảm bảo dùng $image->image
            $image->delete();

            return response()->json(['success' => true]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }
}
