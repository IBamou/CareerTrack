<?php

namespace App\Http\Controllers;

use App\Models\Company;
use App\Models\JobApplication;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SearchController extends Controller
{
    public function index(Request $request)
    {
        $query = $request->query('q', '');
        $type = in_array($request->query('type', 'all'), ['all', 'applications', 'companies']) ? $request->query('type', 'all') : 'all';

        $userId = Auth::id();

        $applications = collect();
        $companies = collect();
        $appCount = 0;
        $companyCount = 0;

        if (empty($query)) {
            return view('search.index', [
                'query' => '',
                'type' => $type,
                'applications' => $applications,
                'companies' => $companies,
                'totalResults' => 0,
                'appCount' => 0,
                'companyCount' => 0,
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

        $totalResults = $appCount + $companyCount;

        return view('search.index', compact('query', 'type', 'applications', 'companies', 'totalResults', 'appCount', 'companyCount'));
    }
}
