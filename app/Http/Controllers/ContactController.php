<?php

namespace App\Http\Controllers;

use App\Models\Contact;
use finfo;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class ContactController extends Controller
{

    public function store(Request $request)
    {

        $formFields = $request->validate([

            'name' => 'required',
            'email' => 'email',
            'number' => ['nullable', Rule::unique('contacts', 'number')],
            'address' => 'nullable',
            'relation' => 'required',
            'profile' => 'nullable',
            'description' => 'nullable',
        ]);

        $formFields['user_id'] = auth()->id();

        return Contact::create($formFields);
    }

    public function update(Request $request, Contact $contact)
    {

        // Make sure logged in user is owner
        if ($contact->user_id != auth()->id()) {
            abort(403, 'Unauthorized Action');
        }

        $formFields = $request->validate([

            'name' => 'required',
            'email' => 'email',
            'number' => ['nullable', Rule::unique('contacts', 'number')],
            'address' => 'nullable',
            'relation' => 'required',
            'profile' => 'nullable',
            'description' => 'nullable',

        ]);


        $contact->update($formFields);
        return $contact;
    }
    public function index()
    {
        return  auth()->user()->contacts;
    }
}