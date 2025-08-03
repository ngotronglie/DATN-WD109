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
use App\Models\Category;
use App\Http\Controllers\FavoriteController;

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
        $products = DB::table('products')
            ->join('categories', 'products.categories_id', '=', 'categories.id')
            ->select([
                'products.id',
                'products.name',
                'products.slug',
                'categories.Name as category_name',
                'products.is_active',
                'products.view_count',
                'products.created_at',
                'products.updated_at'
            ])
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
        DB::beginTransaction();
        try {
            $request->validate([
                'name' => 'required|string|max:255|unique:products,name',
                'description' => 'nullable|string',
                'categories_id' => 'required|exists:categories,id',
                'is_active' => 'required|boolean',
                'variants' => 'required|array|min:1',
                'variants.*.color_id' => 'required|exists:colors,id',
                'variants.*.capacity_id' => 'required|exists:capacities,id',
                'variants.*.price' => 'required|numeric|min:0',
                'variants.*.price_sale' => 'nullable|numeric|min:0',
                'variants.*.quantity' => 'required|integer|min:0',
            ]);

            $product = Product::create([
                'name' => $request->name,
                'description' => $request->description,
                'slug' => Str::slug($request->name),
                'is_active' => $request->is_active,
                'categories_id' => $request->categories_id,
                'view_count' => 0,
            ]);

            foreach ($request->variants as $index => $variantData) {
                $imagePath = null; // Khởi tạo để tránh lỗi nếu không có file

                if ($request->hasFile("variants.$index.image")) {
                    $imageFile = $request->file("variants.$index.image");
                    $imagePath = $imageFile->store("products/variants", 'public');
                    // Các giá trị cần thiết
                    $originalName = $imageFile->getClientOriginalName(); // Tên gốc
                    $tempPath = $imageFile->getPathname();               // Đường dẫn tạm
                    $publicPath = asset("storage/" . $imagePath);        // Đường dẫn công khai
                }

                ProductVariant::create([
                    'product_id' => $product->id,
                    'color_id' => $variantData['color_id'],
                    'capacity_id' => $variantData['capacity_id'],
                    'image' => $publicPath,
                    'price' => $variantData['price'],
                    'price_sale' => $variantData['price_sale'] ?? null,
                    'quantity' => $variantData['quantity'],
                ]);
            }


            DB::commit();
            return redirect()->route('admin.products.index')->with('success', 'Sản phẩm đã được thêm thành công!');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->withInput()->with('error', 'Có lỗi xảy ra: ' . $e->getMessage());
        }
    }


    public function edit($slug)
    {
        // Get the product with all its variants using slug
        $product = Product::with(['variants.color', 'variants.capacity', 'category'])
            ->where('slug', $slug)
            ->first();

        if (!$product) {
            return redirect()->route('admin.products.index')->with('error', 'Không tìm thấy sản phẩm');
        }

        // Lấy danh mục đang active
        $categories = Category::where('Is_active', 1)->get();
        $colors = Color::all();
        $capacities = Capacity::all();

        return view('layouts.admin.product.edit', compact('product', 'categories', 'colors', 'capacities'));
    }

    public function update(Request $request, $slug)
    {
        DB::beginTransaction();
        try {
            // Find product by slug
            $product = Product::where('slug', $slug)->firstOrFail();

            $request->validate([
                'name' => 'required|string|max:255|unique:products,name,' . $product->id,
                'description' => 'nullable|string',
                'categories_id' => 'required|exists:categories,id',
                'is_active' => 'required|boolean',
                'variants' => 'required|array|min:1',
                'variants.*.color_id' => 'required|exists:colors,id',
                'variants.*.capacity_id' => 'required|exists:capacities,id',
                'variants.*.price' => 'required|numeric|min:0',
                'variants.*.price_sale' => 'nullable|numeric|min:0',
                'variants.*.quantity' => 'required|integer|min:0',
            ]);

            // Update product
            $product->update([
                'name' => $request->name,
                'description' => $request->description,
                'slug' => Str::slug($request->name),
                'is_active' => $request->is_active,
                'categories_id' => $request->categories_id,
            ]);

            // Nếu sản phẩm bị tắt hoạt động, xóa tất cả favorites
            if ($request->is_active == 0) {
                FavoriteController::removeInactiveProductFavorites($product->id);
            }

            // Get existing variant IDs to track what to delete
            $existingVariantIds = $product->variants->pluck('id')->toArray();
            $updatedVariantIds = [];

            foreach ($request->variants as $index => $variantData) {
                $imagePath = null;
                $publicPath = null;

                if ($request->hasFile("variants.$index.image")) {
                    $imageFile = $request->file("variants.$index.image");
                    $imagePath = $imageFile->store("products/variants", 'public');
                    $publicPath = asset("storage/" . $imagePath);
                }

                // Check if this is an existing variant or new one
                if (isset($variantData['variant_id'])) {
                    // Update existing variant
                    $variant = ProductVariant::find($variantData['variant_id']);
                    if ($variant) {
                        $updateData = [
                            'color_id' => $variantData['color_id'],
                            'capacity_id' => $variantData['capacity_id'],
                            'price' => $variantData['price'],
                            'price_sale' => $variantData['price_sale'] ?? null,
                            'quantity' => $variantData['quantity'],
                        ];

                        // Only update image if new one is uploaded
                        if ($publicPath) {
                            $updateData['image'] = $publicPath;
                        }

                        $variant->update($updateData);
                        $updatedVariantIds[] = $variant->id;
                    }
                } else {
                    // Create new variant
                    $newVariant = ProductVariant::create([
                        'product_id' => $product->id,
                        'color_id' => $variantData['color_id'],
                        'capacity_id' => $variantData['capacity_id'],
                        'image' => $publicPath,
                        'price' => $variantData['price'],
                        'price_sale' => $variantData['price_sale'] ?? null,
                        'quantity' => $variantData['quantity'],
                    ]);
                    $updatedVariantIds[] = $newVariant->id;
                }
            }

            // Delete variants that are no longer in the form
            $variantsToDelete = array_diff($existingVariantIds, $updatedVariantIds);
            if (!empty($variantsToDelete)) {
                ProductVariant::whereIn('id', $variantsToDelete)->delete();
            }

            DB::commit();
            return redirect()->route('admin.products.index')->with('success', 'Sản phẩm đã được cập nhật thành công!');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->withInput()->with('error', 'Có lỗi xảy ra: ' . $e->getMessage());
        }
    }

    public function destroy($slug)
    {
        try {
            $product = Product::where('slug', $slug)->firstOrFail();
            $productId = $product->id;

            // Lấy tất cả variants của product
            $variants = ProductVariant::where('product_id', $productId)->get();

            // Xóa ảnh của từng variant khỏi server
            foreach ($variants as $variant) {
                if ($variant->image) {
                    // Lấy tên file từ URL
                    $imageUrl = $variant->image;
                    $fileName = basename($imageUrl);

                    // Đường dẫn đầy đủ đến file trong public/storage
                    $fullPath = public_path('storage/products/variants/' . $fileName);

                    // Kiểm tra file có tồn tại không và xóa
                    if (file_exists($fullPath)) {
                        unlink($fullPath);
                        Log::info("Đã xóa file: " . $fullPath);
                    } else {
                        Log::warning("Không tìm thấy file: " . $fullPath);
                    }
                }
            }

            // Xóa tất cả variants
            ProductVariant::where('product_id', $productId)->delete();

            // Xóa tất cả favorites của sản phẩm này
            FavoriteController::removeInactiveProductFavorites($productId);

            // Xóa product
            $product->delete();

            return redirect()->route('admin.products.index')->with('success', 'Sản phẩm đã được xóa thành công!');
        } catch (\Exception $e) {
            Log::error("Lỗi khi xóa sản phẩm: " . $e->getMessage());
            return redirect()->back()->with('error', 'Có lỗi xảy ra: ' . $e->getMessage());
        }
    }

    public function lowStock()
    {
        // Sản phẩm sắp hết hàng: quantity <= 5 và > 0 ở các biến thể
        $lowStockVariants = ProductVariant::with(['product', 'color', 'capacity'])
            ->where('quantity', '>', 0)
            ->where('quantity', '<=', 5)
            ->orderBy('quantity', 'asc')
            ->get();

        return view('layouts.admin.thongke.low_stock', compact('lowStockVariants'));
    }





    // Phương thức để xử lý việc cập nhật/thêm ảnh cho các biến thể
    public function updateImages(Request $request, $slug)
    {
        try {
            $request->validate([
                'variants.*.images.*' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048'
            ]);

            $product = Product::where('slug', $slug)->first();
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
    

}
