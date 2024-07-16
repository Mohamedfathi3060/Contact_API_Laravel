<?php

namespace App\Http\Controllers;

use App\Models\Contact;
use App\Models\Phone;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class PhonesController extends Controller
{
    public function index(Contact $contact)
    {
        // fetch all emails of this
        return response($contact->phones()->get(),Response::HTTP_OK);

    }
    public function store(Request $request ,Contact $contact)
    {
        // store an email for this Contact
        $contact->phones()->create(['phone'=>$request->phone]);
        $contact->load(['emails','phones']);
        return response($contact,Response::HTTP_CREATED);
    }
    public function update(Phone $phone, Request $request)
    {
        $phone->update($request->all());
        return response($phone,Response::HTTP_OK);
    }
    public function destroy(Phone $phone)
    {
        $phone->delete();
        return response('',Response::HTTP_NO_CONTENT);
    }
}
