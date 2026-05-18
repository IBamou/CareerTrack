<?php

namespace App\Http\Controllers;

use App\Enums\JobApplicationStatus;
use App\Http\Requests\JobApplication\StoreJobApplicationRequest;
use App\Http\Requests\JobApplication\UpdateJobApplicationRequest;
use App\Http\Requests\JobApplication\UpdateStatusJobApplicationRequest;
use App\Models\Company;
use App\Models\JobApplication;
use Illuminate\Support\Facades\Auth;

class JobApplicationController extends Controller
{
    public function index()
    {
        $userId = Auth::id();

        $jobApplications = JobApplication::with('company')
            ->where('applied_by', $userId)
            ->latest()
            ->paginate(15);

        $stats = [
            'total' => JobApplication::where('applied_by', $userId)->count(),
            'active' => JobApplication::where('applied_by', $userId)
                ->whereIn('status', [JobApplicationStatus::Applied, JobApplicationStatus::InReview])
                ->count(),
            'interviews' => JobApplication::where('applied_by', $userId)
                ->whereIn('status', [
                    JobApplicationStatus::HrInterview,
                    JobApplicationStatus::TechnicalInterview,
                    JobApplicationStatus::FinalInterview,
                ])
                ->count(),
            'offers' => JobApplication::where('applied_by', $userId)
                ->whereIn('status', [JobApplicationStatus::Offer, JobApplicationStatus::Accepted])
                ->count(),
        ];

        return view('jobApplication.index', compact('jobApplications', 'stats'));
    }

    public function archives()
    {
        $jobApplications = JobApplication::onlyTrashed()
            ->with('company')
            ->where('applied_by', Auth::id())
            ->latest()
            ->paginate(15);

        return view('jobApplication.archives', compact('jobApplications'));
    }

    public function create()
    {
        $companies = Company::where('user_id', Auth::id())->get();

        return view('jobApplication.create', compact('companies'));
    }

    public function store(StoreJobApplicationRequest $request)
    {
        $validated = $request->validated();
        $validated['applied_by'] = Auth::id();
        $validated['links'] ??= [];
        $validated['notes'] ??= '';
        $validated['location_city'] ??= '';

        if (filled($request->new_company_name)) {
            $company = Company::firstOrCreate(
                ['name' => $request->new_company_name],
                ['user_id' => Auth::id(), 'location' => ''],
            );
            $validated['company_id'] = $company->id;
        }

        unset($validated['new_company_name']);

        JobApplication::create($validated);

        return redirect()->route('job-applications.index');
    }

    public function show(JobApplication $jobApplication)
    {
        $this->authorize('view', $jobApplication);

        return view('jobApplication.show', compact('jobApplication'));
    }

    public function edit(JobApplication $jobApplication)
    {
        $companies = Company::where('user_id', Auth::id())->get();

        return view('jobApplication.edit', compact('jobApplication', 'companies'));
    }

    public function update(UpdateJobApplicationRequest $request, JobApplication $jobApplication)
    {
        $this->authorize('update', $jobApplication);

        $validated = $request->validated();
        $validated['links'] ??= [];
        $validated['notes'] ??= '';
        $validated['location_city'] ??= '';

        if (filled($request->new_company_name)) {
            $company = Company::firstOrCreate(
                ['name' => $request->new_company_name],
                ['user_id' => Auth::id(), 'location' => ''],
            );
            $validated['company_id'] = $company->id;
        }

        unset($validated['new_company_name']);

        $jobApplication->update($validated);

        return redirect()->route('job-applications.show', ['jobApplication' => $jobApplication->id]);
    }

    public function updateStatus(UpdateStatusJobApplicationRequest $request, JobApplication $jobApplication)
    {
        $this->authorize('update', $jobApplication);

        $validated = $request->validated();
        $jobApplication->update($validated);

        return redirect()->route('job-applications.show', ['jobApplication' => $jobApplication->id]);
    }

    public function archive(JobApplication $jobApplication)
    {
        $this->authorize('archive', $jobApplication);

        $jobApplication->delete();

        return redirect()->route('job-applications.index');
    }

    public function restore(JobApplication $jobApplication)
    {
        $this->authorize('restore', $jobApplication);

        $jobApplication->restore();

        return redirect()->route('job-applications.index');

    }

    public function forceDelete(JobApplication $jobApplication)
    {
        $this->authorize('forceDelete', $jobApplication);

        $jobApplication->forceDelete();

        return redirect()->route('job-applications.index');
    }
}
