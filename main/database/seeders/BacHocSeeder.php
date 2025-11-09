<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\BacHoc;

class BacHocSeeder extends Seeder
{
    public function run(): void
    {
        $bacHocs = [
            ['ma' => 'CD', 'ten' => 'Cao đẳng'],
            ['ma' => 'DH', 'ten' => 'Đại học'],
            ['ma' => 'THS', 'ten' => 'Thạc sĩ'],
            ['ma' => 'TS', 'ten' => 'Tiến sĩ'],
        ];

        foreach ($bacHocs as $bacHoc) {
            BacHoc::create($bacHoc);
        }
    }
}
