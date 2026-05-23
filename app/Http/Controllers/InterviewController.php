<?php

namespace App\Http\Controllers;

use App\Http\Requests\Interview\StoreInterviewRequest;
use App\Http\Requests\Interview\UpdateInterviewRequest;
use App\Models\Interview;
use App\Models\JobApplication;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class InterviewController extends Controller
{
    public function index(Request $request)
    {
        $interviews = Interview::with('jobApplication.company')
            ->where('user_id', Auth::id())
            ->when($request->filled('q'), function ($q) use ($request) {
                $q->where(function ($query) use ($request) {
                    $query->where('type', 'like', '%' . $request->q . '%')
                        ->orWhere('notes', 'like', '%' . $request->q . '%')
                        ->orWhereHas('jobApplication', function ($jq) use ($request) {
                            $jq->where('job_title', 'like', '%' . $request->q . '%')
                                ->orWhereHas('company', function ($cq) use ($request) {
                                    $cq->where('name', 'like', '%' . $request->q . '%');
                                });
                        });
                });
            })
            ->latest()
            ->paginate(15)
            ->withQueryString();

        return view('interview.index', compact('interviews'));
    }

    public function archives()
    {
        $interviews = Interview::onlyTrashed()
            ->with('jobApplication')
            ->where('user_id', Auth::id())
            ->latest()
            ->paginate(15);

        return view('interview.archives', compact('interviews'));
    }

    public function create()
    {
        $jobApplications = JobApplication::where('applied_by', Auth::id())->get();

        return view('interview.create', compact('jobApplications'));
    }

    public function store(StoreInterviewRequest $request)
    {
        $validated = $request->validated();
        $validated['user_id'] = Auth::id();

        Interview::create($validated);

        return redirect()->route('interviews.index')->with('status', 'Interview created.');
    }

    public function show(Interview $interview)
    {
        $this->authorize('view', $interview);

        return view('interview.show', compact('interview'));
    }

    public function edit(Interview $interview)
    {
        $this->authorize('update', $interview);

        $jobApplications = JobApplication::where('applied_by', Auth::id())->get();

        return view('interview.edit', compact('interview', 'jobApplications'));
    }

    public function update(UpdateInterviewRequest $request, Interview $interview)
    {
        $this->authorize('update', $interview);

        $interview->update($request->validated());

        return redirect()->route('interviews.show', $interview)->with('status', 'Interview updated.');
    }

    public function archive(Interview $interview)
    {
        $this->authorize('archive', $interview);

        $interview->delete();

        return redirect()->route('interviews.index')->with('status', 'Interview archived.');
    }

    public function restore(Interview $interview)
    {
        $this->authorize('restore', $interview);

        $interview->restore();

        return redirect()->route('interviews.index')->with('status', 'Interview restored.');
    }

    public function forceDelete(Interview $interview)
    {
        $this->authorize('forceDelete', $interview);

        $interview->forceDelete();

        return redirect()->route('interviews.index')->with('status', 'Interview permanently deleted.');
    }
}
