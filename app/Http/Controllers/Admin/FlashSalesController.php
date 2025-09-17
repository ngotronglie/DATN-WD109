<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\FlashSale;
use App\Models\FlashSaleProduct;
use App\Models\ProductVariant;
use App\Models\Product;
use App\Models\Categories;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

class FlashSalesController extends Controller
{
    /**
     * Display a listing of flash sales
     */
    public function index()
    {
        $flashSales = FlashSale::with(['flashSaleProducts.productVariant.product'])
                              ->orderBy('created_at', 'desc')
                              ->paginate(10);

        return view('layouts.admin.flash_sales.index', compact('flashSales'));
    }

    /**
     * Show the form for creating a new flash sale
     */
    public function create()
    {
        $categories = Categories::where('Is_active', 1)->get();
        return view('layouts.admin.flash_sales.create', compact('categories'));
    }

    /**
     * Store a newly created flash sale
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'start_time' => 'required|date|after_or_equal:now',
            'end_time' => 'required|date|after:start_time',
            'is_active' => 'required|boolean',
            'products' => 'required|array|min:1',
            'products.*.product_variant_id' => 'required|exists:product_variants,id',
            'products.*.sale_price' => 'required|numeric|min:0',
            'products.*.sale_quantity' => 'required|integer|min:1',
            'products.*.priority' => 'nullable|integer|min:0|max:999',
            'products.*.status' => 'nullable|in:active,inactive,featured',
        ]);

        // Cấm trùng thời gian toàn cục: tại bất kỳ thời điểm chỉ có 1 Flash Sale đang diễn ra
        // Chỉ áp dụng kiểm tra khi tạo sale ở trạng thái hoạt động
        if ((int) $request->is_active === 1) {
            $start = Carbon::parse($request->start_time);
            $end = Carbon::parse($request->end_time);

            $overlap = FlashSale::where('is_active', 1)
                ->where(function($q) use ($start, $end) {
                    $q->where('start_time', '<', $end)
                      ->where('end_time', '>', $start);
                })
                ->exists();

            if ($overlap) {
                return redirect()->back()
                    ->withInput()
                    ->withErrors(['time' => 'Đã có Flash Sale khác đang (hoặc sẽ) diễn ra trong khoảng thời gian này.']);
            }
        }

        DB::beginTransaction();
        try {
            // Tạo flash sale
            $flashSale = FlashSale::create([
                'name' => $request->name,
                'start_time' => $request->start_time,
                'end_time' => $request->end_time,
                'is_active' => $request->is_active,
            ]);

            // Xử lý products array - sửa lỗi từ memory
            if (!empty($request->products) && is_array($request->products)) {
                foreach ($request->products as $productData) {
                    $productVariant = ProductVariant::find($productData['product_variant_id']);
                    
                    if (!$productVariant) {
                        throw new \Exception("Product variant không tồn tại: {$productData['product_variant_id']}");
                    }

                    // Tự động lấy original_price từ ProductVariant
                    $originalPrice = $productVariant->price;

                    // Validate sale_price không được lớn hơn original_price
                    if ($productData['sale_price'] >= $originalPrice) {
                        throw new \Exception("Giá flash sale phải nhỏ hơn giá gốc cho sản phẩm {$productVariant->product->name}");
                    }

                    // Validate sale_quantity không được lớn hơn stock hiện có
                    if ($productData['sale_quantity'] > $productVariant->quantity) {
                        throw new \Exception("Số lượng flash sale không được lớn hơn stock hiện có cho sản phẩm {$productVariant->product->name}");
                    }

                    // Tạo FlashSaleProduct record
                    FlashSaleProduct::create([
                        'flash_sale_id' => $flashSale->id,
                        'product_variant_id' => $productData['product_variant_id'],
                        'sale_price' => $productData['sale_price'],
                        'sale_quantity' => $productData['sale_quantity'],
                        'initial_stock' => $productData['sale_quantity'],
                        'remaining_stock' => $productData['sale_quantity'],
                        'original_price' => $originalPrice,
                        'priority' => $productData['priority'] ?? 0,
                        'status' => $productData['status'] ?? 'active',
                    ]);
                }
            }

            DB::commit();
            return redirect()->route('admin.flash-sales.index')->with('success', 'Flash sale đã được tạo thành công!');

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error creating flash sale: ' . $e->getMessage());
            return redirect()->back()->withInput()->with('error', 'Có lỗi xảy ra: ' . $e->getMessage());
        }
    }

    /**
     * Display the specified flash sale
     */
    public function show($id)
    {
        $flashSale = FlashSale::with([
            'flashSaleProducts.productVariant.product',
            'flashSaleProducts.productVariant.color',
            'flashSaleProducts.productVariant.capacity'
        ])->findOrFail($id);

        return view('layouts.admin.flash_sales.show', compact('flashSale'));
    }

    /**
     * Show the form for editing the specified flash sale
     */
    public function edit($id)
    {
        $flashSale = FlashSale::with([
            'flashSaleProducts.productVariant.product',
            'flashSaleProducts.productVariant.color',
            'flashSaleProducts.productVariant.capacity'
        ])->findOrFail($id);

        $categories = Categories::where('Is_active', 1)->get();

        return view('layouts.admin.flash_sales.edit', compact('flashSale', 'categories'));
    }

    /**
     * Update the specified flash sale
     */
    public function update(Request $request, $id)
    {
        $flashSale = FlashSale::findOrFail($id);

        $request->validate([
            'name' => 'required|string|max:255',
            'start_time' => 'required|date',
            'end_time' => 'required|date|after:start_time',
            'is_active' => 'required|boolean',
            'products' => 'required|array|min:1',
            'products.*.product_variant_id' => 'required|exists:product_variants,id',
            'products.*.sale_price' => 'required|numeric|min:0',
            'products.*.sale_quantity' => 'required|integer|min:1',
            'products.*.priority' => 'nullable|integer|min:0|max:999',
            'products.*.status' => 'nullable|in:active,inactive,featured',
        ]);

        // Cấm trùng thời gian toàn cục khi cập nhật (loại trừ chính bản ghi đang sửa)
        if ((int) $request->is_active === 1) {
            $start = Carbon::parse($request->start_time);
            $end = Carbon::parse($request->end_time);

            $overlap = FlashSale::where('id', '!=', $flashSale->id)
                ->where('is_active', 1)
                ->where(function($q) use ($start, $end) {
                    $q->where('start_time', '<', $end)
                      ->where('end_time', '>', $start);
                })
                ->exists();

            if ($overlap) {
                return redirect()->back()
                    ->withInput()
                    ->withErrors(['time' => 'Khoảng thời gian bị trùng với một Flash Sale khác đang hoạt động.']);
            }
        }

        DB::beginTransaction();
        try {
            // Cập nhật flash sale
            $flashSale->update([
                'name' => $request->name,
                'start_time' => $request->start_time,
                'end_time' => $request->end_time,
                'is_active' => $request->is_active,
            ]);

            // Xóa tất cả flash sale products cũ
            $flashSale->flashSaleProducts()->delete();

            // Tạo lại flash sale products
            foreach ($request->products as $productData) {
                $productVariant = ProductVariant::find($productData['product_variant_id']);
                $originalPrice = $productVariant->price;

                if ($productData['sale_price'] >= $originalPrice) {
                    throw new \Exception("Giá flash sale phải nhỏ hơn giá gốc cho sản phẩm {$productVariant->product->name}");
                }

                if ($productData['sale_quantity'] > $productVariant->quantity) {
                    throw new \Exception("Số lượng flash sale không được lớn hơn stock hiện có cho sản phẩm {$productVariant->product->name}");
                }

                FlashSaleProduct::create([
                    'flash_sale_id' => $flashSale->id,
                    'product_variant_id' => $productData['product_variant_id'],
                    'sale_price' => $productData['sale_price'],
                    'sale_quantity' => $productData['sale_quantity'],
                    'initial_stock' => $productData['sale_quantity'],
                    'remaining_stock' => $productData['sale_quantity'],
                    'original_price' => $originalPrice,
                    'priority' => $productData['priority'] ?? 0,
                    'status' => $productData['status'] ?? 'active',
                ]);
            }

            DB::commit();
            return redirect()->route('admin.flash-sales.index')->with('success', 'Flash sale đã được cập nhật thành công!');

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error updating flash sale: ' . $e->getMessage());
            return redirect()->back()->withInput()->with('error', 'Có lỗi xảy ra: ' . $e->getMessage());
        }
    }

    /**
     * Remove the specified flash sale
     */
    public function destroy($id)
    {
        try {
            $flashSale = FlashSale::findOrFail($id);
            
            // Kiểm tra flash sale có đang diễn ra không
            if ($flashSale->isOngoing()) {
                return redirect()->back()->with('error', 'Không thể xóa flash sale đang diễn ra!');
            }

            $flashSale->delete(); // Cascade delete sẽ xóa flash_sale_products

            return redirect()->route('admin.flash-sales.index')->with('success', 'Flash sale đã được xóa thành công!');

        } catch (\Exception $e) {
            Log::error('Error deleting flash sale: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Có lỗi xảy ra: ' . $e->getMessage());
        }
    }

    /**
     * Toggle flash sale status
     */
    public function toggleStatus($id)
    {
        try {
            $flashSale = FlashSale::findOrFail($id);
            $flashSale->is_active = !$flashSale->is_active;
            $flashSale->save();

            $status = $flashSale->is_active ? 'kích hoạt' : 'tắt';
            return redirect()->back()->with('success', "Flash sale đã được {$status} thành công!");

        } catch (\Exception $e) {
            Log::error('Error toggling flash sale status: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Có lỗi xảy ra: ' . $e->getMessage());
        }
    }

    /**
     * Get products by category for AJAX
     */
    public function getByCategory(Request $request)
    {
        $categoryId = $request->input('category_id');
        
        $products = Product::with(['variants' => function($query) {
                                $query->where('quantity', '>', 0);
                            }, 'variants.color', 'variants.capacity'])
                          ->where('categories_id', $categoryId)
                          ->where('is_active', 1)
                          ->whereHas('variants', function($query) {
                              $query->where('quantity', '>', 0);
                          })
                          ->get();

        $result = [];
        foreach ($products as $product) {
            $variants = [];
            foreach ($product->variants as $variant) {
                $variants[] = [
                    'id' => $variant->id,
                    'color_id' => $variant->color_id,
                    'color_name' => $variant->color->name ?? '',
                    'capacity_id' => $variant->capacity_id,
                    'capacity_name' => $variant->capacity->name ?? '',
                    'price' => $variant->price,
                    'price_sale' => $variant->price_sale,
                    'quantity' => $variant->quantity,
                    'image' => $variant->image,
                ];
            }
            
            if (!empty($variants)) {
                $result[] = [
                    'id' => $product->id,
                    'name' => $product->name,
                    'variants' => $variants,
                ];
            }
        }

        return response()->json($result);
    }

    /**
     * Get flash sale statistics
     */
    public function statistics()
    {
        $stats = [
            'total' => FlashSale::count(),
            'active' => FlashSale::active()->count(),
            'ongoing' => FlashSale::ongoing()->count(),
            'upcoming' => FlashSale::upcoming()->count(),
            'expired' => FlashSale::where('end_time', '<', Carbon::now())->count(),
        ];

        return view('layouts.admin.flash_sales.statistics', compact('stats'));
    }

    /**
     * JSON: Live stats for a flash sale (for admin show auto-update)
     */
    public function apiStats($id)
    {
        $flashSale = FlashSale::with([
            'flashSaleProducts:id,flash_sale_id,product_variant_id,sale_quantity,initial_stock,remaining_stock,original_price,sale_price,status,priority',
            'flashSaleProducts.productVariant:id,product_id,color_id,capacity_id'
        ])->findOrFail($id);

        $items = [];
        $sumSaleQty = 0;
        $sumRemaining = 0;
        $sumSold = 0;

        foreach ($flashSale->flashSaleProducts as $item) {
            $sold = (int) $item->initial_stock - (int) $item->remaining_stock;
            $items[] = [
                'variant_id' => (int) $item->product_variant_id,
                'sale_quantity' => (int) $item->sale_quantity,
                'initial_stock' => (int) $item->initial_stock,
                'remaining_stock' => (int) $item->remaining_stock,
                'sold' => (int) max(0, $sold),
                'status' => (string) ($item->status ?? 'active'),
                'sale_price' => (float) $item->sale_price,
                'original_price' => (float) $item->original_price,
            ];
            $sumSaleQty += (int) $item->sale_quantity;
            $sumRemaining += (int) $item->remaining_stock;
            $sumSold += (int) max(0, $sold);
        }

        return response()->json([
            'success' => true,
            'summary' => [
                'sale_quantity' => $sumSaleQty,
                'remaining' => $sumRemaining,
                'sold' => $sumSold,
            ],
            'items' => $items,
        ]);
    }
}
