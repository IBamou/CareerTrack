<?php

namespace App\Http\Controllers;

use App\Http\Requests\Company\StoreCompanyRequest;
use App\Http\Requests\Company\UpdateCompanyRequest;
use App\Models\Company;
use App\Models\Tag;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CompanyController extends Controller
{
    public function index(Request $request)
    {
        $companies = Company::withCount('jobApplications')
            ->where('user_id', Auth::id())
            ->when($request->filled('q'), function ($q) use ($request) {
                $q->where('name', 'like', '%'.$request->q.'%');
            })
            ->latest()
            ->paginate(15)
            ->withQueryString();

        return view('company.index', compact('companies'));
    }

    public function archives()
    {
        $companies = Company::onlyTrashed()
            ->withCount('jobApplications')
            ->where('user_id', Auth::id())
            ->latest()
            ->paginate(15);

        return view('company.archives', compact('companies'));
    }

    public function create()
    {
        return view('company.create');
    }

    public function store(StoreCompanyRequest $request)
    {
        $validated = $request->validated();
        $validated['user_id'] = Auth::id();
        Company::create($validated);

        return redirect()->route('companies.index')->with('status', 'Company created.');
    }

    public function show(Request $request, Company $company)
    {
        $this->authorize('view', $company);

        $company->loadCount('jobApplications')->load(['documents', 'tags']);
        $contacts = $company->contacts()
            ->latest()
            ->paginate(10);
        $jobApplications = $company->jobApplications()
            ->with('company')
            ->latest()
            ->paginate(10);

        $availableTags = Tag::where('user_id', Auth::id())->orderBy('name')->get();

        return view('company.show', compact('company', 'contacts', 'jobApplications', 'availableTags'));
    }

    public function toggleTag(Request $request, Company $company)
    {
        $this->authorize('update', $company);

        $request->validate(['tag_id' => 'required|exists:tags,id']);

        $tag = Tag::find($request->tag_id);

        if ($tag->user_id !== Auth::id()) {
            abort(403);
        }

        $company->tags()->toggle($tag->id);

        return redirect()->route('companies.show', $company);
    }

    public function edit(Company $company)
    {
        $this->authorize('update', $company);

        return view('company.edit', compact('company'));
    }

    public function update(UpdateCompanyRequest $request, Company $company)
    {
        $this->authorize('update', $company);

        $company->update($request->validated());

        return redirect()->route('companies.show', $company)->with('status', 'Company updated.');
    }

    public function archive(Company $company)
    {
        $this->authorize('archive', $company);

        $company->delete();

        return redirect()->route('companies.index')->with('status', 'Company archived.');
    }

    public function restore(Company $company)
    {
        $this->authorize('restore', $company);

        $company->restore();

        return redirect()->route('companies.index')->with('status', 'Company restored.');
    }

    public function forceDelete(Company $company)
    {
        $this->authorize('forceDelete', $company);

        $company->forceDelete();

        return redirect()->route('companies.index')->with('status', 'Company permanently deleted.');
    }
}
