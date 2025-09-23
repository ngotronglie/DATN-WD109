<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class TinhThanhSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $provinces = [
            ['ma_tinh' => '01', 'ten_tinh' => 'Hà Nội'],
            ['ma_tinh' => '02', 'ten_tinh' => 'Hồ Chí Minh'],
            ['ma_tinh' => '03', 'ten_tinh' => 'Đà Nẵng'],
            ['ma_tinh' => '04', 'ten_tinh' => 'Hải Phòng'],
            ['ma_tinh' => '05', 'ten_tinh' => 'Cần Thơ'],
            ['ma_tinh' => '06', 'ten_tinh' => 'An Giang'],
            ['ma_tinh' => '07', 'ten_tinh' => 'Bà Rịa - Vũng Tàu'],
            ['ma_tinh' => '08', 'ten_tinh' => 'Bạc Liêu'],
            ['ma_tinh' => '09', 'ten_tinh' => 'Bắc Giang'],
            ['ma_tinh' => '10', 'ten_tinh' => 'Bắc Kạn'],
            ['ma_tinh' => '11', 'ten_tinh' => 'Bắc Ninh'],
            ['ma_tinh' => '12', 'ten_tinh' => 'Bến Tre'],
            ['ma_tinh' => '13', 'ten_tinh' => 'Bình Dương'],
            ['ma_tinh' => '14', 'ten_tinh' => 'Bình Phước'],
            ['ma_tinh' => '15', 'ten_tinh' => 'Bình Thuận'],
            ['ma_tinh' => '16', 'ten_tinh' => 'Cà Mau'],
            ['ma_tinh' => '17', 'ten_tinh' => 'Cao Bằng'],
            ['ma_tinh' => '18', 'ten_tinh' => 'Đắk Lắk'],
            ['ma_tinh' => '19', 'ten_tinh' => 'Đắk Nông'],
            ['ma_tinh' => '20', 'ten_tinh' => 'Điện Biên'],
            ['ma_tinh' => '21', 'ten_tinh' => 'Đồng Nai'],
            ['ma_tinh' => '22', 'ten_tinh' => 'Đồng Tháp'],
            ['ma_tinh' => '23', 'ten_tinh' => 'Gia Lai'],
            ['ma_tinh' => '24', 'ten_tinh' => 'Hà Giang'],
            ['ma_tinh' => '25', 'ten_tinh' => 'Hà Nam'],
            ['ma_tinh' => '26', 'ten_tinh' => 'Hà Tĩnh'],
            ['ma_tinh' => '27', 'ten_tinh' => 'Hải Dương'],
            ['ma_tinh' => '28', 'ten_tinh' => 'Hậu Giang'],
            ['ma_tinh' => '29', 'ten_tinh' => 'Hòa Bình'],
            ['ma_tinh' => '30', 'ten_tinh' => 'Hưng Yên'],
            ['ma_tinh' => '31', 'ten_tinh' => 'Khánh Hòa'],
            ['ma_tinh' => '32', 'ten_tinh' => 'Kiên Giang'],
            ['ma_tinh' => '33', 'ten_tinh' => 'Kon Tum'],
            ['ma_tinh' => '34', 'ten_tinh' => 'Lai Châu'],
            ['ma_tinh' => '35', 'ten_tinh' => 'Lâm Đồng'],
            ['ma_tinh' => '36', 'ten_tinh' => 'Lạng Sơn'],
            ['ma_tinh' => '37', 'ten_tinh' => 'Lào Cai'],
            ['ma_tinh' => '38', 'ten_tinh' => 'Long An'],
            ['ma_tinh' => '39', 'ten_tinh' => 'Nam Định'],
            ['ma_tinh' => '40', 'ten_tinh' => 'Nghệ An'],
            ['ma_tinh' => '41', 'ten_tinh' => 'Ninh Bình'],
            ['ma_tinh' => '42', 'ten_tinh' => 'Ninh Thuận'],
            ['ma_tinh' => '43', 'ten_tinh' => 'Phú Thọ'],
            ['ma_tinh' => '44', 'ten_tinh' => 'Phú Yên'],
            ['ma_tinh' => '45', 'ten_tinh' => 'Quảng Bình'],
            ['ma_tinh' => '46', 'ten_tinh' => 'Quảng Nam'],
            ['ma_tinh' => '47', 'ten_tinh' => 'Quảng Ngãi'],
            ['ma_tinh' => '48', 'ten_tinh' => 'Quảng Ninh'],
            ['ma_tinh' => '49', 'ten_tinh' => 'Quảng Trị'],
            ['ma_tinh' => '50', 'ten_tinh' => 'Sóc Trăng'],
            ['ma_tinh' => '51', 'ten_tinh' => 'Sơn La'],
            ['ma_tinh' => '52', 'ten_tinh' => 'Tây Ninh'],
            ['ma_tinh' => '53', 'ten_tinh' => 'Thái Bình'],
            ['ma_tinh' => '54', 'ten_tinh' => 'Thái Nguyên'],
            ['ma_tinh' => '55', 'ten_tinh' => 'Thanh Hóa'],
            ['ma_tinh' => '56', 'ten_tinh' => 'Thừa Thiên Huế'],
            ['ma_tinh' => '57', 'ten_tinh' => 'Tiền Giang'],
            ['ma_tinh' => '58', 'ten_tinh' => 'Trà Vinh'],
            ['ma_tinh' => '59', 'ten_tinh' => 'Tuyên Quang'],
            ['ma_tinh' => '60', 'ten_tinh' => 'Vĩnh Long'],
            ['ma_tinh' => '61', 'ten_tinh' => 'Vĩnh Phúc'],
            ['ma_tinh' => '62', 'ten_tinh' => 'Yên Bái'],
        ];

        foreach ($provinces as $province) {
            \DB::table('tinhthanh')->insert([
                'ma_tinh' => $province['ma_tinh'],
                'ten_tinh' => $province['ten_tinh'],
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}
