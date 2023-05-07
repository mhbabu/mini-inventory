<?php

use App\Modules\Supplier\Models\Supplier;
use Illuminate\Database\Seeder;

class SupplierTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Supplier::create([
            'name' => 'Mahadi Hassan Babu',
            'company_id' => 1,
            'supplier_id_no' => 'S-0000',
            'email' => 'mahadihassan.cse@gmail.com',
            'mobile' => '01795232590',
            'address' => 'Dhaka, Uttara',
            'status' => 1
        ]);

        Supplier::create([
            'name' => 'Masud Rana',
            'company_id' => 2,
            'supplier_id_no' => 'S-0001',
            'email' => 'masud@gmail.com',
            'mobile' => '01795232591',
            'address' => 'Dhaka, Uttara',
            'status' => 1
        ]);

        Supplier::create([
            'name' => 'Shohag Rana',
            'company_id' => 3,
            'supplier_id_no' => 'S-0002',
            'email' => 'shohag@gmail.com',
            'mobile' => '01795232592',
            'address' => 'Dhaka, Khilkhet',
            'status' => 1
        ]);
    }
}
