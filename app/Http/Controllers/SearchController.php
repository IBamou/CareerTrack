<?php

namespace App\Http\Controllers;

use App\Models\Company;
use App\Models\Contact;
use App\Models\JobApplication;
use App\Models\Tag;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SearchController extends Controller
{
    public function index(Request $request)
    {
        $query = $request->query('q', '');
        $type = in_array($request->query('type', 'all'), ['all', 'applications', 'companies', 'contacts', 'tags']) ? $request->query('type', 'all') : 'all';

        $userId = Auth::id();

        $applications = collect();
        $companies = collect();
        $contacts = collect();
        $tags = collect();
        $appCount = 0;
        $companyCount = 0;
        $contactCount = 0;
        $tagCount = 0;

        if (empty($query)) {
            return view('search.index', [
                'query' => '',
                'type' => $type,
                'applications' => $applications,
                'companies' => $companies,
                'contacts' => $contacts,
                'totalResults' => 0,
                'appCount' => 0,
                'companyCount' => 0,
                'contactCount' => 0,
            ]);
        }

        $appCount = JobApplication::where('applied_by', $userId)
            ->where(function ($q) use ($query) {
                $q->where('job_title', 'like', "%{$query}%")
                    ->orWhere('location_city', 'like', "%{$query}%")
                    ->orWhere('notes', 'like', "%{$query}%");
            })->count();

        $companyCount = Company::where('user_id', $userId)
            ->where(function ($q) use ($query) {
                $q->where('name', 'like', "%{$query}%")
                    ->orWhere('industry', 'like', "%{$query}%")
                    ->orWhere('location', 'like', "%{$query}%")
                    ->orWhere('notes', 'like', "%{$query}%");
            })->count();

        $contactCount = Contact::where('user_id', $userId)
            ->where(function ($q) use ($query) {
                $q->where('name', 'like', "%{$query}%")
                    ->orWhere('role', 'like', "%{$query}%")
                    ->orWhere('email', 'like', "%{$query}%")
                    ->orWhere('notes', 'like', "%{$query}%");
            })->count();

        $tagCount = Tag::where('user_id', $userId)
            ->where('name', 'like', "%{$query}%")
            ->count();

        if ($type === 'all' || $type === 'applications') {
            $applications = JobApplication::with('company')
                ->where('applied_by', $userId)
                ->where(function ($q) use ($query) {
                    $q->where('job_title', 'like', "%{$query}%")
                        ->orWhere('location_city', 'like', "%{$query}%")
                        ->orWhere('notes', 'like', "%{$query}%");
                })
                ->latest()
                ->paginate(20)
                ->withQueryString();
        }

        if ($type === 'all' || $type === 'companies') {
            $companies = Company::where('user_id', $userId)
                ->where(function ($q) use ($query) {
                    $q->where('name', 'like', "%{$query}%")
                        ->orWhere('industry', 'like', "%{$query}%")
                        ->orWhere('location', 'like', "%{$query}%")
                        ->orWhere('notes', 'like', "%{$query}%");
                })
                ->latest()
                ->paginate(20)
                ->withQueryString();
        }

        if ($type === 'all' || $type === 'contacts') {
            $contacts = Contact::with('company')
                ->where('user_id', $userId)
                ->where(function ($q) use ($query) {
                    $q->where('name', 'like', "%{$query}%")
                        ->orWhere('role', 'like', "%{$query}%")
                        ->orWhere('email', 'like', "%{$query}%")
                        ->orWhere('notes', 'like', "%{$query}%");
                })
                ->latest()
                ->paginate(20)
                ->withQueryString();
        }

        if ($type === 'all' || $type === 'tags') {
            $tags = Tag::withCount(['jobApplications', 'companies', 'contacts'])
                ->where('user_id', $userId)
                ->where('name', 'like', "%{$query}%")
                ->orderBy('name')
                ->paginate(20)
                ->withQueryString();
        }

        $totalResults = $appCount + $companyCount + $contactCount + $tagCount;

        return view('search.index', compact('query', 'type', 'applications', 'companies', 'contacts', 'tags', 'totalResults', 'appCount', 'companyCount', 'contactCount', 'tagCount'));
    }
}
