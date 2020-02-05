<?php

use Illuminate\Database\Seeder;
use App\Company;
use App\Job;

class JobSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $companies = Company::all();

        foreach($companies as $company)
        {
            factory(Job::class, rand(1,3))->create([
                'company_id' => $company->id
            ]);
        }
    }
}
