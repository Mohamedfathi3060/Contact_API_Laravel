<?php

namespace App\Http\Controllers;

use App\Models\Contact;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;


class ContactsController extends Controller
{
    public function index()
    {
        // TODO
        // all return all records in DB without any related objects
        // to allow loading related object
        // you should use eager loading by
        // 1) make the relation in model File
        // 2) query using 'MODEL_NAME::with('relationName')->get();'
        return response(Contact::all(),Response::HTTP_OK);
    }
    public function show(Contact $contact)
    {
        return response($contact,Response::HTTP_OK);
    }
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|unique:contacts,title,',
            'phone' => [
                'required_without:email',
                'unique:contacts,phone,',
                'regex:/^0[0-9]{10}$/u'
            ],
            'email' => 'required_without:phone|email|unique:contacts,email,',
        ]);
        // TODO
        // Don't use factory in controllers
        // only in Tests
        $newCont = Contact::create($request->all());
        return response($newCont,Response::HTTP_CREATED);
    }
    public function update(Request $request,Contact $contact)
    {
        // TODO nullable vs sometimes
        //  to allow phone without email
        //      or
        //  to allow email without phone
        $request->validate([
            'title' => 'sometimes|unique:contacts,title,' . $contact->id,
            'phone' => [
                'sometimes',
                'unique:contacts,phone,' . $contact->id,
                'regex:/^0[0-9]{10}$/u'
            ],
            'email' => 'sometimes|email|unique:contacts,email,' . $contact->id
        ]);
        $contact->update($request->all());
        return response($contact,Response::HTTP_OK);

    }
    public function destroy(Contact $contact){
        $contact->delete();
        return response('',Response::HTTP_NO_CONTENT);
    }
}
