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
use App\Models\ImageVariant;

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

            return redirect()->route('admin.product.index')
                ->with('success', 'Sản phẩm đã được thêm thành công!');
        } catch (\Exception $e) {
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
            $product = $this->product->loadOneID($id);
            if (!$product) {
                return redirect()->back()->with('error', 'Không tìm thấy sản phẩm!');
            }

            $allVariantFiles = $request->file('variants');

            // Đảm bảo $allVariantFiles là một mảng hoặc đối tượng có thể lặp, nếu không thì là mảng rỗng.
            $allVariantFiles = is_array($allVariantFiles) || is_object($allVariantFiles) ? $allVariantFiles : [];

            foreach ($allVariantFiles as $variantId => $variantData) {
                // Đảm bảo $variantData là một mảng và chứa key 'images'
                if (!is_array($variantData) || !isset($variantData['images'])) {
                    continue; // Bỏ qua nếu dữ liệu biến thể không hợp lệ hoặc không có key 'images'
                }

                $rawImages = $variantData['images']; // Lấy giá trị thô của 'images'

                $imagesToProcess = [];

                if ($rawImages instanceof \Illuminate\Http\UploadedFile) {
                    // Nếu là một đối tượng UploadedFile duy nhất
                    $imagesToProcess[] = $rawImages;
                } elseif (is_array($rawImages)) {
                    // Nếu là một mảng, lọc để chỉ giữ lại các đối tượng UploadedFile hợp lệ
                    $imagesToProcess = array_filter($rawImages, function ($item) {
                        return $item instanceof \Illuminate\Http\UploadedFile && $item->isValid();
                    });
                }
                // Nếu $rawImages là null, chuỗi rỗng, hoặc bất kỳ loại không phải file nào khác, $imagesToProcess sẽ vẫn là một mảng rỗng.

                // Chỉ xử lý nếu có ảnh hợp lệ để duyệt
                if (empty($imagesToProcess)) {
                    continue;
                }

                foreach ($imagesToProcess as $image) {
                    $path = $image->store('products/variants', 'public');
                    $product->variants()->find($variantId)->images()->create([
                        'image' => $path,
                        'variant_id' => $variantId,
                    ]);
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
