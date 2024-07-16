<?php

namespace App\Http\Controllers;

use App\Models\Contact;
use App\Models\Email;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EmailsController extends Controller
{
    public function index(Contact $contact)
    {
        // fetch all emails of this
        return response($contact->emails()->get(),Response::HTTP_OK);

    }
    public function store(Request $request ,Contact $contact)
    {
        // store an email for this Contact
        $contact->emails()->create(['email'=>$request->email]);
        $contact->load(['emails','phones']);
        return response($contact,Response::HTTP_CREATED);
    }
    public function update(Email $email, Request $request)
    {
        $email->update($request->all());
        return response($email,Response::HTTP_OK);
    }
    public function destroy(Email $email)
    {
        $email->delete();
        return response('',Response::HTTP_NO_CONTENT);
    }
}
