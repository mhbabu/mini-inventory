<?php

use App\Modules\Company\Models\Company;
use Illuminate\Database\Seeder;

class CompanyTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Company::create([
            'name' => 'Bashundhara Group',
            'status' => 1
        ]);

        Company::create([
            'name' => 'BEXIMCO',
            'status' => 1
        ]);

        Company::create([
            'name' => 'Walton',
            'status' => 1
        ]);
    }
}
