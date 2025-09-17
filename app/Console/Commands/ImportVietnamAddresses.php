<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class ImportVietnamAddresses extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'address:import {--source=https://raw.githubusercontent.com/kenzouno1/DiaGioiHanhChinhVN/master/data.json}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Import full Vietnam provinces/districts/wards into tinhthanh, devvn_quanhuyen, devvn_xaphuongthitran tables';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        if (!Schema::hasTable('tinhthanh') || !Schema::hasTable('devvn_quanhuyen') || !Schema::hasTable('devvn_xaphuongthitran')) {
            $this->error('Required tables do not exist. Please run migrations first.');
            return self::FAILURE;
        }

        $source = $this->option('source');
        $this->info('Downloading dataset from: ' . $source);

        try {
            $context = stream_context_create([
                'http' => ['timeout' => 60],
                'https' => ['timeout' => 60],
            ]);
            $json = file_get_contents($source, false, $context);
            if ($json === false) {
                throw new \RuntimeException('Failed to download dataset');
            }
            $data = json_decode($json, true);
            if (!is_array($data)) {
                throw new \RuntimeException('Invalid dataset format');
            }
        } catch (\Throwable $e) {
            $this->error('Error fetching dataset: ' . $e->getMessage());
            return self::FAILURE;
        }

        $this->info('Importing provinces, districts, and wards...');

        DB::beginTransaction();
        try {
            // Provinces
            $provinceRows = [];
            foreach ($data as $province) {
                $provinceId = (string)($province['Id'] ?? '');
                $provinceName = (string)($province['Name'] ?? '');
                if ($provinceId === '' || $provinceName === '') { continue; }
                $provinceRows[] = [
                    'ma_tinh' => $provinceId,
                    'ten_tinh' => $provinceName,
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
            }
            if (!empty($provinceRows)) {
                DB::table('tinhthanh')->upsert($provinceRows, ['ma_tinh'], ['ten_tinh', 'updated_at']);
            }

            // Ensure province codes (ma_tinh) match official dataset by name
            $existing = DB::table('tinhthanh')->get(['id', 'ten_tinh', 'ma_tinh']);
            $nameToOfficial = [];
            foreach ($data as $province) {
                $pid = (string)($province['Id'] ?? '');
                $pname = (string)($province['Name'] ?? '');
                if ($pid && $pname) $nameToOfficial[$pname] = $pid;
            }
            foreach ($existing as $row) {
                $desired = $nameToOfficial[$row->ten_tinh] ?? null;
                if ($desired && $desired !== $row->ma_tinh) {
                    // Check if another row already has desired code
                    $conflict = DB::table('tinhthanh')->where('ma_tinh', $desired)->first();
                    if ($conflict && $conflict->id !== $row->id) {
                        // Conflict: delete the wrong-coded duplicate by name to keep unique constraint clean
                        DB::table('tinhthanh')->where('id', $row->id)->delete();
                    } else {
                        DB::table('tinhthanh')->where('id', $row->id)->update(['ma_tinh' => $desired, 'updated_at' => now()]);
                    }
                }
            }

            // Districts and Wards
            $districtRows = [];
            $wardRows = [];
            foreach ($data as $province) {
                $provinceId = (string)($province['Id'] ?? '');
                $districts = $province['Districts'] ?? [];
                if ($provinceId === '' || !is_array($districts)) { continue; }
                foreach ($districts as $district) {
                    $districtId = (string)($district['Id'] ?? '');
                    $districtName = (string)($district['Name'] ?? '');
                    if ($districtId === '' || $districtName === '') { continue; }
                    $districtRows[] = [
                        'maqh' => $districtId,
                        'name' => $districtName,
                        'matp' => $provinceId,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ];

                    $wards = $district['Wards'] ?? [];
                    if (!is_array($wards)) { continue; }
                    foreach ($wards as $ward) {
                        $wardId = (string)($ward['Id'] ?? '');
                        $wardName = (string)($ward['Name'] ?? '');
                        if ($wardId === '' || $wardName === '') { continue; }
                        $wardRows[] = [
                            'xaid' => $wardId,
                            'name' => $wardName,
                            'maqh' => $districtId,
                            'created_at' => now(),
                            'updated_at' => now(),
                        ];
                    }
                }
            }

            // Chunked upserts to avoid memory/time issues
            foreach (array_chunk($districtRows, 1000) as $chunk) {
                DB::table('devvn_quanhuyen')->upsert($chunk, ['maqh'], ['name', 'matp', 'updated_at']);
            }
            foreach (array_chunk($wardRows, 1000) as $chunk) {
                DB::table('devvn_xaphuongthitran')->upsert($chunk, ['xaid'], ['name', 'maqh', 'updated_at']);
            }

            DB::commit();
            $this->info('Import completed successfully.');
        } catch (\Throwable $e) {
            DB::rollBack();
            $this->error('Import failed: ' . $e->getMessage());
            return self::FAILURE;
        }

        return self::SUCCESS;
    }
}


