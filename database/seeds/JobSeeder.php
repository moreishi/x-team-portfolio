<?php

use Illuminate\Database\Seeder;
use App\Company;
use App\Job;
use App\Tag;

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

        $jobs = Job::all();

        foreach($jobs as $job)
        {
            for($i = 0; $i < rand(3,5); $i++)
            {
                $job->tags()->attach(Tag::where('id',rand(1,100))->first());
            }
        }
    }
}
