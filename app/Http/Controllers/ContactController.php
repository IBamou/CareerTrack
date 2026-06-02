<?php

namespace App\Http\Controllers;

use App\Http\Requests\Contact\StoreContactRequest;
use App\Http\Requests\Contact\UpdateContactRequest;
use App\Models\Company;
use App\Models\Contact;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ContactController extends Controller
{
    public function index(Request $request)
    {
        $contacts = Contact::where('user_id', Auth::id())
            ->with('company')
            ->when($request->filled('q'), function ($q) use ($request) {
                $search = $request->q;
                $q->where(function ($query) use ($search) {
                    $query->where('name', 'like', "%{$search}%")
                        ->orWhere('email', 'like', "%{$search}%")
                        ->orWhere('role', 'like', "%{$search}%")
                        ->orWhereHas('company', function ($cq) use ($search) {
                            $cq->where('name', 'like', "%{$search}%");
                        });
                });
            })
            ->orderBy('name')
            ->paginate(15)
            ->withQueryString();

        return view('contact.index', compact('contacts'));
    }

    public function archives()
    {
        $contacts = Contact::where('user_id', Auth::id())
            ->onlyTrashed()
            ->with('company')
            ->orderBy('name')
            ->paginate(15);

        return view('contact.archives', compact('contacts'));
    }

    public function create()
    {
        $companies = Company::where('user_id', Auth::id())->orderBy('name')->get();

        return view('contact.create', compact('companies'));
    }

    public function store(StoreContactRequest $request)
    {
        $contact = Contact::create([
            ...$request->validated(),
            'user_id' => Auth::id(),
        ]);

        return redirect()->route('contacts.show', $contact)
            ->with('status', 'Contact created.');
    }

    public function show(Contact $contact)
    {
        $this->authorize('view', $contact);

        return view('contact.show', compact('contact'));
    }

    public function edit(Contact $contact)
    {
        $this->authorize('update', $contact);
        $companies = Company::where('user_id', Auth::id())->orderBy('name')->get();

        return view('contact.edit', compact('contact', 'companies'));
    }

    public function update(UpdateContactRequest $request, Contact $contact)
    {
        $this->authorize('update', $contact);
        $contact->update($request->validated());

        return redirect()->route('contacts.show', $contact)
            ->with('status', 'Contact updated.');
    }

    public function archive(Contact $contact)
    {
        $this->authorize('archive', $contact);
        $contact->delete();

        return redirect()->route('contacts.index')
            ->with('status', 'Contact archived.');
    }

    public function restore(Contact $contact)
    {
        $this->authorize('restore', $contact);
        $contact->restore();

        return redirect()->route('contacts.show', $contact)
            ->with('status', 'Contact restored.');
    }

    public function forceDelete(Contact $contact)
    {
        $this->authorize('forceDelete', $contact);
        $contact->forceDelete();

        return redirect()->route('contacts.archives')
            ->with('status', 'Contact permanently deleted.');
    }
}
