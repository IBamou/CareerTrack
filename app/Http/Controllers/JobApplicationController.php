<?php

namespace App\Http\Controllers;

use App\Enums\JobApplicationStatus;
use App\Http\Requests\JobApplication\StoreJobApplicationRequest;
use App\Http\Requests\JobApplication\UpdateJobApplicationRequest;
use App\Http\Requests\JobApplication\UpdateStatusJobApplicationRequest;
use App\Models\Company;
use App\Models\Document;
use App\Models\JobApplication;
use App\Models\Tag;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class JobApplicationController extends Controller
{
    public function index(Request $request)
    {
        $userId = Auth::id();

        $jobApplications = JobApplication::with(['company', 'interviews'])
            ->where('applied_by', $userId)
            ->when($request->filled('status'), function ($q) use ($request) {
                $q->where('status', $request->status);
            })
            ->when($request->filled('priority'), function ($q) use ($request) {
                $q->where('priority', $request->priority);
            })
            ->when($request->filled('q'), function ($q) use ($request) {
                $q->where(function ($query) use ($request) {
                    $query->where('job_title', 'like', '%'.$request->q.'%')
                        ->orWhereHas('company', function ($cq) use ($request) {
                            $cq->where('name', 'like', '%'.$request->q.'%');
                        });
                });
            })
            ->latest()
            ->paginate(15)
            ->withQueryString();

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

    public function kanban()
    {
        $applications = JobApplication::where('applied_by', Auth::id())
            ->with('company')
            ->orderBy('updated_at', 'desc')
            ->get()
            ->groupBy(fn ($app) => $app->status->value);

        return view('jobApplication.kanban', compact('applications'));
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
        $tags = Tag::where('user_id', Auth::id())->orderBy('name')->get();

        return view('jobApplication.create', compact('companies', 'tags'));
    }

    public function store(StoreJobApplicationRequest $request)
    {
        $validated = $request->validated();
        $validated['applied_by'] = Auth::id();
        $validated['links'] = collect($validated['links'] ?? [])->mapWithKeys(fn ($link) => [$link['label'] => $link['url']])->all();
        $validated['notes'] ??= '';
        $validated['location_city'] ??= '';

        if (filled($request->new_company_name)) {
            $company = Company::firstOrCreate(
                ['name' => $request->new_company_name, 'user_id' => Auth::id()],
                ['location' => ''],
            );
            $validated['company_id'] = $company->id;
        }

        unset($validated['new_company_name']);

        $application = JobApplication::create($validated);

        if ($request->filled('tags')) {
            $application->tags()->sync($request->tags);
        }

        if ($request->hasFile('documents')) {
            foreach ($request->file('documents') as $file) {
                $path = $file->store('documents/'.Auth::id(), 'public');
                Document::create([
                    'user_id' => Auth::id(),
                    'documentable_type' => get_class($application),
                    'documentable_id' => $application->id,
                    'name' => $file->getClientOriginalName(),
                    'path' => $path,
                    'mime_type' => $file->getMimeType(),
                    'size' => $file->getSize(),
                ]);
            }
        }

        return redirect()->route('job-applications.show', $application);
    }

    public function show(Request $request, JobApplication $jobApplication)
    {
        $this->authorize('view', $jobApplication);

        $jobApplication->load('tags');

        $interviews = $jobApplication->interviews()
            ->orderBy('scheduled_at', 'desc')
            ->paginate(10, ['*'], 'interviews_page');

        $documents = $jobApplication->documents()
            ->latest()
            ->paginate(10, ['*'], 'documents_page');

        $activities = $jobApplication->activities()
            ->orderBy('created_at', 'desc')
            ->paginate(10, ['*'], 'activities_page');

        $availableTags = Tag::where('user_id', Auth::id())->orderBy('name')->get();

        return view('jobApplication.show', compact('jobApplication', 'interviews', 'documents', 'activities', 'availableTags'));
    }

    public function edit(JobApplication $jobApplication)
    {
        $this->authorize('update', $jobApplication);

        $companies = Company::where('user_id', Auth::id())->get();
        $tags = Tag::where('user_id', Auth::id())->orderBy('name')->get();

        return view('jobApplication.edit', compact('jobApplication', 'companies', 'tags'));
    }

    public function update(UpdateJobApplicationRequest $request, JobApplication $jobApplication)
    {
        $this->authorize('update', $jobApplication);

        $validated = $request->validated();
        $validated['links'] = collect($validated['links'] ?? [])->mapWithKeys(fn ($link) => [$link['label'] => $link['url']])->all();
        $validated['notes'] ??= '';
        $validated['location_city'] ??= '';

        if (filled($request->new_company_name)) {
            $company = Company::firstOrCreate(
                ['name' => $request->new_company_name, 'user_id' => Auth::id()],
                ['location' => ''],
            );
            $validated['company_id'] = $company->id;
        }

        unset($validated['new_company_name']);

        $jobApplication->update($validated);

        if ($request->filled('tags')) {
            $jobApplication->tags()->sync($request->tags);
        }

        return redirect()->route('job-applications.show', ['jobApplication' => $jobApplication->id]);
    }

    public function updateStatus(UpdateStatusJobApplicationRequest $request, JobApplication $jobApplication)
    {
        $this->authorize('update', $jobApplication);

        $validated = $request->validated();
        $jobApplication->update($validated);

        if ($request->expectsJson()) {
            return response()->json([
                'status' => $jobApplication->status->value,
                'label' => $jobApplication->status->label(),
            ]);
        }

        return redirect()->route('job-applications.show', ['jobApplication' => $jobApplication->id]);
    }

    public function toggleTag(Request $request, JobApplication $jobApplication)
    {
        $this->authorize('update', $jobApplication);

        $request->validate(['tag_id' => 'required|exists:tags,id']);

        $tag = Tag::find($request->tag_id);

        if ($tag->user_id !== Auth::id()) {
            abort(403);
        }

        $jobApplication->tags()->toggle($tag->id);

        return redirect()->route('job-applications.show', $jobApplication);
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

    public function bulkAction(Request $request)
    {
        $ids = $request->input('selected', []);
        $action = $request->input('bulk_action');
        $userId = Auth::id();

        if (empty($ids) || ! $action) {
            return redirect()->route('job-applications.index');
        }

        $applications = JobApplication::whereIn('id', $ids)
            ->where('applied_by', $userId)
            ->get();

        if ($action === 'archive') {
            foreach ($applications as $app) {
                if (Auth::user()->can('archive', $app)) {
                    $app->delete();
                }
            }
        } elseif ($action === 'change_status' && $request->filled('bulk_status')) {
            $validStatuses = array_column(JobApplicationStatus::cases(), 'value');
            if (! in_array($request->bulk_status, $validStatuses, true)) {
                return redirect()->route('job-applications.index');
            }
            foreach ($applications as $app) {
                if (Auth::user()->can('update', $app)) {
                    $app->update(['status' => $request->bulk_status]);
                }
            }
        }

        return redirect()->route('job-applications.index');
    }

    public function addLink(Request $request, JobApplication $jobApplication)
    {
        $this->authorize('update', $jobApplication);

        $validated = $request->validate([
            'label' => 'required|string|max:255',
            'url' => 'required|url:http,https',
        ]);

        $links = $jobApplication->links ?? [];
        $links[$validated['label']] = $validated['url'];
        $jobApplication->update(['links' => $links]);

        return redirect()->route('job-applications.show', $jobApplication)
            ->with('status', 'Link added.');
    }

    public function deleteLink(Request $request, JobApplication $jobApplication)
    {
        $this->authorize('update', $jobApplication);

        $validated = $request->validate(['key' => 'required|string']);

        $links = $jobApplication->links ?? [];

        if (array_key_exists($validated['key'], $links)) {
            unset($links[$validated['key']]);
            $jobApplication->update(['links' => $links]);
        }

        return redirect()->route('job-applications.show', $jobApplication)
            ->with('status', 'Link removed.');
    }

    public function export()
    {
        $applications = JobApplication::where('applied_by', Auth::id())
            ->with('company')
            ->orderBy('created_at', 'desc')
            ->get();

        $filename = 'job-applications-'.now()->format('Y-m-d').'.csv';
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => "attachment; filename=\"$filename\"",
        ];

        $callback = function () use ($applications) {
            $handle = fopen('php://output', 'w');
            fputcsv($handle, ['Job Title', 'Company', 'Status', 'Priority', 'Location', 'Location Type', 'Applied At', 'Salary Min', 'Salary Max', 'Currency', 'Notes', 'Created At']);

            foreach ($applications as $app) {
                fputcsv($handle, [
                    $app->job_title,
                    $app->company?->name ?? '',
                    $app->status->value,
                    $app->priority,
                    $app->location_city ?? '',
                    $app->location_type?->value ?? '',
                    $app->applied_at?->format('Y-m-d') ?? '',
                    $app->salary_min ?? '',
                    $app->salary_max ?? '',
                    $app->currency ?? '',
                    $app->notes ?? '',
                    $app->created_at->format('Y-m-d'),
                ]);
            }

            fclose($handle);
        };

        return response()->stream($callback, 200, $headers);
    }
}
