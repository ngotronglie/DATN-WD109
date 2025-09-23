<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PhuongXaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $wards = [
            // Quận Ba Đình (001)
            ['xaid' => '00001', 'name' => 'Phường Phúc Xá', 'maqh' => '001'],
            ['xaid' => '00002', 'name' => 'Phường Trúc Bạch', 'maqh' => '001'],
            ['xaid' => '00003', 'name' => 'Phường Vĩnh Phú', 'maqh' => '001'],
            ['xaid' => '00004', 'name' => 'Phường Cống Vị', 'maqh' => '001'],
            ['xaid' => '00005', 'name' => 'Phường Liễu Giai', 'maqh' => '001'],
            ['xaid' => '00006', 'name' => 'Phường Nguyễn Trung Trực', 'maqh' => '001'],
            ['xaid' => '00007', 'name' => 'Phường Quán Thánh', 'maqh' => '001'],
            ['xaid' => '00008', 'name' => 'Phường Ngọc Hà', 'maqh' => '001'],
            ['xaid' => '00009', 'name' => 'Phường Điện Biên', 'maqh' => '001'],
            ['xaid' => '00010', 'name' => 'Phường Đội Cấn', 'maqh' => '001'],
            ['xaid' => '00011', 'name' => 'Phường Ngọc Khánh', 'maqh' => '001'],
            ['xaid' => '00012', 'name' => 'Phường Kim Mã', 'maqh' => '001'],
            ['xaid' => '00013', 'name' => 'Phường Giảng Võ', 'maqh' => '001'],
            ['xaid' => '00014', 'name' => 'Phường Thành Công', 'maqh' => '001'],

            // Quận Hoàn Kiếm (002)
            ['xaid' => '00015', 'name' => 'Phường Phúc Tân', 'maqh' => '002'],
            ['xaid' => '00016', 'name' => 'Phường Đồng Xuân', 'maqh' => '002'],
            ['xaid' => '00017', 'name' => 'Phường Hàng Mã', 'maqh' => '002'],
            ['xaid' => '00018', 'name' => 'Phường Hàng Buồm', 'maqh' => '002'],
            ['xaid' => '00019', 'name' => 'Phường Hàng Đào', 'maqh' => '002'],
            ['xaid' => '00020', 'name' => 'Phường Hàng Bồ', 'maqh' => '002'],
            ['xaid' => '00021', 'name' => 'Phường Cửa Đông', 'maqh' => '002'],
            ['xaid' => '00022', 'name' => 'Phường Lý Thái Tổ', 'maqh' => '002'],
            ['xaid' => '00023', 'name' => 'Phường Hàng Bạc', 'maqh' => '002'],
            ['xaid' => '00024', 'name' => 'Phường Hàng Gai', 'maqh' => '002'],
            ['xaid' => '00025', 'name' => 'Phường Chương Dương Độ', 'maqh' => '002'],
            ['xaid' => '00026', 'name' => 'Phường Hàng Trống', 'maqh' => '002'],
            ['xaid' => '00027', 'name' => 'Phường Cửa Nam', 'maqh' => '002'],
            ['xaid' => '00028', 'name' => 'Phường Hàng Bông', 'maqh' => '002'],
            ['xaid' => '00029', 'name' => 'Phường Lý Thường Kiệt', 'maqh' => '002'],
            ['xaid' => '00030', 'name' => 'Phường Hàng Đậu', 'maqh' => '002'],
            ['xaid' => '00031', 'name' => 'Phường Ông Ích Khiêm', 'maqh' => '002'],
            ['xaid' => '00032', 'name' => 'Phường Hàng Mã', 'maqh' => '002'],
            ['xaid' => '00033', 'name' => 'Phường Hàng Chiếu', 'maqh' => '002'],

            // Quận 1 (031) - Hồ Chí Minh
            ['xaid' => '00034', 'name' => 'Phường Tân Định', 'maqh' => '031'],
            ['xaid' => '00035', 'name' => 'Phường Đa Kao', 'maqh' => '031'],
            ['xaid' => '00036', 'name' => 'Phường Bến Nghé', 'maqh' => '031'],
            ['xaid' => '00037', 'name' => 'Phường Bến Thành', 'maqh' => '031'],
            ['xaid' => '00038', 'name' => 'Phường Nguyễn Thái Bình', 'maqh' => '031'],
            ['xaid' => '00039', 'name' => 'Phường Phạm Ngũ Lão', 'maqh' => '031'],
            ['xaid' => '00040', 'name' => 'Phường Cầu Ông Lãnh', 'maqh' => '031'],
            ['xaid' => '00041', 'name' => 'Phường Cô Giang', 'maqh' => '031'],
            ['xaid' => '00042', 'name' => 'Phường Nguyễn Cư Trinh', 'maqh' => '031'],
            ['xaid' => '00043', 'name' => 'Phường Cầu Kho', 'maqh' => '031'],

            // Quận 2 (032) - Hồ Chí Minh
            ['xaid' => '00044', 'name' => 'Phường Thủ Thiêm', 'maqh' => '032'],
            ['xaid' => '00045', 'name' => 'Phường An Phú', 'maqh' => '032'],
            ['xaid' => '00046', 'name' => 'Phường An Khánh', 'maqh' => '032'],
            ['xaid' => '00047', 'name' => 'Phường Bình An', 'maqh' => '032'],
            ['xaid' => '00048', 'name' => 'Phường Bình Khánh', 'maqh' => '032'],
            ['xaid' => '00049', 'name' => 'Phường Bình Trưng Đông', 'maqh' => '032'],
            ['xaid' => '00050', 'name' => 'Phường Bình Trưng Tây', 'maqh' => '032'],
            ['xaid' => '00051', 'name' => 'Phường Cát Lái', 'maqh' => '032'],
            ['xaid' => '00052', 'name' => 'Phường Thạnh Mỹ Lợi', 'maqh' => '032'],
            ['xaid' => '00053', 'name' => 'Phường Thủ Thiêm', 'maqh' => '032'],

            // Quận Hải Châu (055) - Đà Nẵng
            ['xaid' => '00054', 'name' => 'Phường Thạch Thang', 'maqh' => '055'],
            ['xaid' => '00055', 'name' => 'Phường Hải Châu I', 'maqh' => '055'],
            ['xaid' => '00056', 'name' => 'Phường Hải Châu II', 'maqh' => '055'],
            ['xaid' => '00057', 'name' => 'Phường Phước Ninh', 'maqh' => '055'],
            ['xaid' => '00058', 'name' => 'Phường Hòa Thuận Tây', 'maqh' => '055'],
            ['xaid' => '00059', 'name' => 'Phường Hòa Thuận Đông', 'maqh' => '055'],
            ['xaid' => '00060', 'name' => 'Phường Nam Dương', 'maqh' => '055'],
            ['xaid' => '00061', 'name' => 'Phường Bình Hiên', 'maqh' => '055'],
            ['xaid' => '00062', 'name' => 'Phường Bình Thuận', 'maqh' => '055'],
            ['xaid' => '00063', 'name' => 'Phường Hòa Cường Bắc', 'maqh' => '055'],
            ['xaid' => '00064', 'name' => 'Phường Hòa Cường Nam', 'maqh' => '055'],
            ['xaid' => '00065', 'name' => 'Phường Thạch Thang', 'maqh' => '055'],
            ['xaid' => '00066', 'name' => 'Phường Khuê Trung', 'maqh' => '055'],

            // Quận Hồng Bàng (063) - Hải Phòng
            ['xaid' => '00067', 'name' => 'Phường Quán Toan', 'maqh' => '063'],
            ['xaid' => '00068', 'name' => 'Phường Hùng Vương', 'maqh' => '063'],
            ['xaid' => '00069', 'name' => 'Phường Sở Dầu', 'maqh' => '063'],
            ['xaid' => '00070', 'name' => 'Phường Thượng Lý', 'maqh' => '063'],
            ['xaid' => '00071', 'name' => 'Phường Hạ Lý', 'maqh' => '063'],
            ['xaid' => '00072', 'name' => 'Phường Minh Khai', 'maqh' => '063'],
            ['xaid' => '00073', 'name' => 'Phường Trại Cau', 'maqh' => '063'],
            ['xaid' => '00074', 'name' => 'Phường Lê Lợi', 'maqh' => '063'],
            ['xaid' => '00075', 'name' => 'Phường Đằng Giang', 'maqh' => '063'],
            ['xaid' => '00076', 'name' => 'Phường Lạc Viên', 'maqh' => '063'],
            ['xaid' => '00077', 'name' => 'Phường Đông Khê', 'maqh' => '063'],
            ['xaid' => '00078', 'name' => 'Phường Cầu Đất', 'maqh' => '063'],
            ['xaid' => '00079', 'name' => 'Phường Gia Viên', 'maqh' => '063'],
            ['xaid' => '00080', 'name' => 'Phường Đằng Lâm', 'maqh' => '063'],
            ['xaid' => '00081', 'name' => 'Phường Lê Chân', 'maqh' => '063'],
            ['xaid' => '00082', 'name' => 'Phường Hàng Kênh', 'maqh' => '063'],

            // Quận Ninh Kiều (078) - Cần Thơ
            ['xaid' => '00083', 'name' => 'Phường Cái Khế', 'maqh' => '078'],
            ['xaid' => '00084', 'name' => 'Phường An Hòa', 'maqh' => '078'],
            ['xaid' => '00085', 'name' => 'Phường Thới Bình', 'maqh' => '078'],
            ['xaid' => '00086', 'name' => 'Phường An Nghiệp', 'maqh' => '078'],
            ['xaid' => '00087', 'name' => 'Phường An Cư', 'maqh' => '078'],
            ['xaid' => '00088', 'name' => 'Phường Tân An', 'maqh' => '078'],
            ['xaid' => '00089', 'name' => 'Phường An Phú', 'maqh' => '078'],
            ['xaid' => '00090', 'name' => 'Phường Xuân Khánh', 'maqh' => '078'],
            ['xaid' => '00091', 'name' => 'Phường Hưng Lợi', 'maqh' => '078'],
            ['xaid' => '00092', 'name' => 'Phường An Khánh', 'maqh' => '078'],
            ['xaid' => '00093', 'name' => 'Phường An Thới', 'maqh' => '078'],
            ['xaid' => '00094', 'name' => 'Phường Bình Thủy', 'maqh' => '078'],
            ['xaid' => '00095', 'name' => 'Phường An Thới', 'maqh' => '078'],
            ['xaid' => '00096', 'name' => 'Phường Trà Nóc', 'maqh' => '078'],
            ['xaid' => '00097', 'name' => 'Phường Trà An', 'maqh' => '078'],
        ];

        foreach ($wards as $ward) {
            \DB::table('devvn_xaphuongthitran')->insert([
                'xaid' => $ward['xaid'],
                'name' => $ward['name'],
                'maqh' => $ward['maqh'],
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}
