<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class QuanHuyenSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $districts = [
            // Hà Nội (01)
            ['maqh' => '001', 'name' => 'Quận Ba Đình', 'matp' => '01'],
            ['maqh' => '002', 'name' => 'Quận Hoàn Kiếm', 'matp' => '01'],
            ['maqh' => '003', 'name' => 'Quận Tây Hồ', 'matp' => '01'],
            ['maqh' => '004', 'name' => 'Quận Long Biên', 'matp' => '01'],
            ['maqh' => '005', 'name' => 'Quận Cầu Giấy', 'matp' => '01'],
            ['maqh' => '006', 'name' => 'Quận Đống Đa', 'matp' => '01'],
            ['maqh' => '007', 'name' => 'Quận Hai Bà Trưng', 'matp' => '01'],
            ['maqh' => '008', 'name' => 'Quận Hoàng Mai', 'matp' => '01'],
            ['maqh' => '009', 'name' => 'Quận Thanh Xuân', 'matp' => '01'],
            ['maqh' => '010', 'name' => 'Huyện Sóc Sơn', 'matp' => '01'],
            ['maqh' => '011', 'name' => 'Huyện Đông Anh', 'matp' => '01'],
            ['maqh' => '012', 'name' => 'Huyện Gia Lâm', 'matp' => '01'],
            ['maqh' => '013', 'name' => 'Quận Nam Từ Liêm', 'matp' => '01'],
            ['maqh' => '014', 'name' => 'Huyện Thanh Trì', 'matp' => '01'],
            ['maqh' => '015', 'name' => 'Quận Bắc Từ Liêm', 'matp' => '01'],
            ['maqh' => '016', 'name' => 'Huyện Mê Linh', 'matp' => '01'],
            ['maqh' => '017', 'name' => 'Quận Hà Đông', 'matp' => '01'],
            ['maqh' => '018', 'name' => 'Thị xã Sơn Tây', 'matp' => '01'],
            ['maqh' => '019', 'name' => 'Huyện Ba Vì', 'matp' => '01'],
            ['maqh' => '020', 'name' => 'Huyện Phúc Thọ', 'matp' => '01'],
            ['maqh' => '021', 'name' => 'Huyện Đan Phượng', 'matp' => '01'],
            ['maqh' => '022', 'name' => 'Huyện Hoài Đức', 'matp' => '01'],
            ['maqh' => '023', 'name' => 'Huyện Quốc Oai', 'matp' => '01'],
            ['maqh' => '024', 'name' => 'Huyện Thạch Thất', 'matp' => '01'],
            ['maqh' => '025', 'name' => 'Huyện Chương Mỹ', 'matp' => '01'],
            ['maqh' => '026', 'name' => 'Huyện Thanh Oai', 'matp' => '01'],
            ['maqh' => '027', 'name' => 'Huyện Thường Tín', 'matp' => '01'],
            ['maqh' => '028', 'name' => 'Huyện Phú Xuyên', 'matp' => '01'],
            ['maqh' => '029', 'name' => 'Huyện Ứng Hòa', 'matp' => '01'],
            ['maqh' => '030', 'name' => 'Huyện Mỹ Đức', 'matp' => '01'],

            // Hồ Chí Minh (02)
            ['maqh' => '031', 'name' => 'Quận 1', 'matp' => '02'],
            ['maqh' => '032', 'name' => 'Quận 2', 'matp' => '02'],
            ['maqh' => '033', 'name' => 'Quận 3', 'matp' => '02'],
            ['maqh' => '034', 'name' => 'Quận 4', 'matp' => '02'],
            ['maqh' => '035', 'name' => 'Quận 5', 'matp' => '02'],
            ['maqh' => '036', 'name' => 'Quận 6', 'matp' => '02'],
            ['maqh' => '037', 'name' => 'Quận 7', 'matp' => '02'],
            ['maqh' => '038', 'name' => 'Quận 8', 'matp' => '02'],
            ['maqh' => '039', 'name' => 'Quận 9', 'matp' => '02'],
            ['maqh' => '040', 'name' => 'Quận 10', 'matp' => '02'],
            ['maqh' => '041', 'name' => 'Quận 11', 'matp' => '02'],
            ['maqh' => '042', 'name' => 'Quận 12', 'matp' => '02'],
            ['maqh' => '043', 'name' => 'Quận Thủ Đức', 'matp' => '02'],
            ['maqh' => '044', 'name' => 'Quận Gò Vấp', 'matp' => '02'],
            ['maqh' => '045', 'name' => 'Quận Bình Thạnh', 'matp' => '02'],
            ['maqh' => '046', 'name' => 'Quận Tân Bình', 'matp' => '02'],
            ['maqh' => '047', 'name' => 'Quận Tân Phú', 'matp' => '02'],
            ['maqh' => '048', 'name' => 'Quận Phú Nhuận', 'matp' => '02'],
            ['maqh' => '049', 'name' => 'Quận Bình Tân', 'matp' => '02'],
            ['maqh' => '050', 'name' => 'Huyện Củ Chi', 'matp' => '02'],
            ['maqh' => '051', 'name' => 'Huyện Hóc Môn', 'matp' => '02'],
            ['maqh' => '052', 'name' => 'Huyện Bình Chánh', 'matp' => '02'],
            ['maqh' => '053', 'name' => 'Huyện Nhà Bè', 'matp' => '02'],
            ['maqh' => '054', 'name' => 'Huyện Cần Giờ', 'matp' => '02'],

            // Đà Nẵng (03)
            ['maqh' => '055', 'name' => 'Quận Hải Châu', 'matp' => '03'],
            ['maqh' => '056', 'name' => 'Quận Thanh Khê', 'matp' => '03'],
            ['maqh' => '057', 'name' => 'Quận Sơn Trà', 'matp' => '03'],
            ['maqh' => '058', 'name' => 'Quận Ngũ Hành Sơn', 'matp' => '03'],
            ['maqh' => '059', 'name' => 'Quận Liên Chiểu', 'matp' => '03'],
            ['maqh' => '060', 'name' => 'Quận Cẩm Lệ', 'matp' => '03'],
            ['maqh' => '061', 'name' => 'Huyện Hòa Vang', 'matp' => '03'],
            ['maqh' => '062', 'name' => 'Huyện Hoàng Sa', 'matp' => '03'],

            // Hải Phòng (04)
            ['maqh' => '063', 'name' => 'Quận Hồng Bàng', 'matp' => '04'],
            ['maqh' => '064', 'name' => 'Quận Ngô Quyền', 'matp' => '04'],
            ['maqh' => '065', 'name' => 'Quận Lê Chân', 'matp' => '04'],
            ['maqh' => '066', 'name' => 'Quận Hải An', 'matp' => '04'],
            ['maqh' => '067', 'name' => 'Quận Kiến An', 'matp' => '04'],
            ['maqh' => '068', 'name' => 'Quận Đồ Sơn', 'matp' => '04'],
            ['maqh' => '069', 'name' => 'Quận Dương Kinh', 'matp' => '04'],
            ['maqh' => '070', 'name' => 'Huyện Thuỷ Nguyên', 'matp' => '04'],
            ['maqh' => '071', 'name' => 'Huyện An Dương', 'matp' => '04'],
            ['maqh' => '072', 'name' => 'Huyện An Lão', 'matp' => '04'],
            ['maqh' => '073', 'name' => 'Huyện Kiến Thuỵ', 'matp' => '04'],
            ['maqh' => '074', 'name' => 'Huyện Tiên Lãng', 'matp' => '04'],
            ['maqh' => '075', 'name' => 'Huyện Vĩnh Bảo', 'matp' => '04'],
            ['maqh' => '076', 'name' => 'Huyện Cát Hải', 'matp' => '04'],
            ['maqh' => '077', 'name' => 'Huyện Bạch Long Vĩ', 'matp' => '04'],

            // Cần Thơ (05)
            ['maqh' => '078', 'name' => 'Quận Ninh Kiều', 'matp' => '05'],
            ['maqh' => '079', 'name' => 'Quận Ô Môn', 'matp' => '05'],
            ['maqh' => '080', 'name' => 'Quận Bình Thuỷ', 'matp' => '05'],
            ['maqh' => '081', 'name' => 'Quận Cái Răng', 'matp' => '05'],
            ['maqh' => '082', 'name' => 'Quận Thốt Nốt', 'matp' => '05'],
            ['maqh' => '083', 'name' => 'Huyện Vĩnh Thạnh', 'matp' => '05'],
            ['maqh' => '084', 'name' => 'Huyện Cờ Đỏ', 'matp' => '05'],
            ['maqh' => '085', 'name' => 'Huyện Phong Điền', 'matp' => '05'],
            ['maqh' => '086', 'name' => 'Huyện Thới Lai', 'matp' => '05'],
        ];

        foreach ($districts as $district) {
            \DB::table('devvn_quanhuyen')->insert([
                'maqh' => $district['maqh'],
                'name' => $district['name'],
                'matp' => $district['matp'],
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}