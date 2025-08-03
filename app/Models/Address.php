<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Address extends Model
{
    use HasFactory;

    protected $table = 'address';
    protected $fillable = [
        'user_id',
        'receiver_name',
        'phone',
        'street',
        'ward',
        'district',
        'city',
        'is_default',
    ];
    // Quan hệ với User
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }       
    // Quan hệ với Order (nếu có bảng orders)
    public function orders()    
    {
        return $this->hasMany(Order::class, 'address');
    }
    // Phương thức để lấy địa chỉ mặc định của người dùng
    public static function getDefaultAddress($userId)   
    {
        return self::where('user_id', $userId)
            ->where('is_default', true)
            ->first();
    }
    // Phương thức để lấy tất cả địa chỉ của người dùng
    public static function getAllAddresses($userId)
    {
        return self::where('user_id', $userId)->get();
    }
    // Phương thức để kiểm tra xem địa chỉ có phải là địa chỉ mặc định không
    public function isDefault()
    {
        return $this->is_default;
    }
    // Phương thức để đặt địa chỉ này là địa chỉ mặc định
    public function setAsDefault()
    {
        // Đặt tất cả các địa chỉ của người dùng này là không mặc định
        self::where('user_id', $this->user_id)->update(['is_default' => false]);
        
        // Đặt địa chỉ hiện tại là mặc định
        $this->is_default = true;
        $this->save();
    }
    // Phương thức để xóa địa chỉ
    public function deleteAddress()
    {
        // Trước khi xóa, kiểm tra xem địa chỉ này có phải là địa chỉ mặc định không
        if ($this->is_default) {
            // Nếu là địa chỉ mặc định, đặt một địa chỉ khác làm mặc định (nếu có)
            $newDefault = self::where('user_id', $this->user_id)
                ->where('id', '!=', $this->id)
                ->first();
            if ($newDefault) {
                $newDefault->setAsDefault();
            }
        }
        
        // Xóa địa chỉ
        return $this->delete();
    }   
    // Phương thức để cập nhật địa chỉ
    public function updateAddress(array $data)
    {
        $this->fill($data);
        return $this->save();
    }
    // Phương thức để tạo địa chỉ mới
    public static function createAddress(array $data)
    {
        $address = new self();
        $address->fill($data);
        $address->save();
        return $address;
    }   
    // Phương thức để tìm địa chỉ theo ID
    public static function findAddressById($id)
    {
        return self::find($id);

}

}