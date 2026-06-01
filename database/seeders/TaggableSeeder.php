<?php

namespace Database\Seeders;

use App\Models\Company;
use App\Models\Contact;
use App\Models\JobApplication;
use App\Models\Tag;
use Illuminate\Database\Seeder;

class TaggableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $tags = Tag::all();
        $applications = JobApplication::all();
        $companies = Company::all();
        $contacts = Contact::all();

        foreach ($applications as $application) {
            $application->tags()->attach(
                $tags->random(rand(1, min(3, $tags->count())))->pluck('id')
            );
        }

        foreach ($companies as $company) {
            $company->tags()->attach(
                $tags->random(rand(1, min(2, $tags->count())))->pluck('id')
            );
        }

        foreach ($contacts as $contact) {
            $contact->tags()->attach(
                $tags->random(rand(1, min(2, $tags->count())))->pluck('id')
            );
        }
    }
}
