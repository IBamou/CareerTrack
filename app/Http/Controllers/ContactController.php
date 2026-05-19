<?php

namespace App\Http\Controllers;

use App\Models\Contact;
use App\Models\Company;
use App\Http\Requests\Contact\StoreContactRequest;
use App\Http\Requests\Contact\UpdateContactRequest;
use Illuminate\Support\Facades\Auth;

class ContactController extends Controller
{
    public function index()
    {
        $contacts = Contact::where('user_id', Auth::id())
            ->with('company')
            ->orderBy('name')
            ->paginate(15);

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
        $this->authorize('delete', $contact);
        $contact->delete();

        return redirect()->route('contacts.index')
            ->with('status', 'Contact archived.');
    }

    public function restore($id)
    {
        $contact = Contact::withTrashed()->findOrFail($id);
        $this->authorize('restore', $contact);
        $contact->restore();

        return redirect()->route('contacts.show', $contact)
            ->with('status', 'Contact restored.');
    }

    public function forceDelete($id)
    {
        $contact = Contact::withTrashed()->findOrFail($id);
        $this->authorize('forceDelete', $contact);
        $contact->forceDelete();

        return redirect()->route('contacts.archives')
            ->with('status', 'Contact permanently deleted.');
    }
}
