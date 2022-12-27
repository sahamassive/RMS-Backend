<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Brand;
class BrandSeederTable extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $brands = [
            ['id'=>1,'name'=>'Bond Bread','status'=>1],
            ['id'=>2,'name'=>'Burrys','status'=>1],
            ['id'=>3,'name'=>'Holsum Bread','status'=>1],
        ];
        Brand::insert($brands);
    }
}
