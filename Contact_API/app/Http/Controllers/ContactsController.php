<?php

namespace App\Http\Controllers;

use App\Http\Requests\contactRequest;
use App\Http\Resources\ContactResource;
use App\Models\Contact;
use App\Models\Email;
use App\Models\Phone;
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
        $data = Contact::with('phones','emails')->get();
        return ContactResource::collection($data)->response()->setStatusCode(Response::HTTP_OK);
    }
    public function show(Contact $contact)
    {
        $contact->load(['emails', 'phones']);
        return (new ContactResource($contact))->response()->setStatusCode(Response::HTTP_OK);
    }
    public function store(contactRequest $request)
    {
        // create the contact it self
        $newCont = Contact::create(['title'=>$request->title]);

        if($request->has('emails')){
            // for each email create email
            foreach ($request->emails as $email ){
                Email::create([
                    'contact_id'=>$newCont->id,
                    'email'=>$email
                ]);
            }
        }
        if($request->has('phones')){
            foreach ($request->phones as $phone ){
                Phone::create([
                    'contact_id'=>$newCont->id,
                    'phone'=>$phone
                ]);
            }
        }
        // USED to join with it's emails and phones
        $newCont->load(['emails', 'phones']);
        return (new ContactResource($newCont))->response()->setStatusCode(Response::HTTP_CREATED);
    }
    public function update(contactRequest $request,Contact $contact)
    {
        $contact->update(['title'=>$request->title]);
        return (new ContactResource($contact))->response()->setStatusCode(Response::HTTP_OK);
    }
    public function destroy(Contact $contact){
        $contact->delete();
        return response()->noContent();
    }
}






// Load vs with
/*
 * both is used for eager loading
 * But with used in query building process
 * while load used after retrieved the model collections
 *  */
// TODO
//  USE request "validation" and resource
