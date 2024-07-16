<?php

namespace Tests\Feature;

use App\Models\Contact;
use App\Models\Phone;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class phoneTest extends TestCase
{
    use RefreshDatabase;
    public function test_fetch_all_phones_of_contact()
    {
        $contact = Contact::factory()->create(['title'=>'ex']);
        $ph1 = Phone::factory()->create(['contact_id'=>$contact->id]);
        $ph2 = Phone::factory()->create(['contact_id'=>$contact->id,'phone'=>'01024068783']);
        $response = $this->getJson(route('contact.phone.index',$contact->id))
            ->assertOk()->json();
        $this->assertEquals($ph1->phone , $response[0]['phone']);
        $this->assertEquals($ph2->phone , $response[1]['phone']);

    }
    public function test_store_a_phone_for_a_contact()
    {
        $contact = Contact::factory()->create(['title'=>'ex']);
        $response = $this->postJson(route('contact.phone.store', $contact->id),[
            'phone' => '01024068783'
        ])
            ->assertCreated()->json();
        $this->assertEquals('01024068783' , $response['phones'][0]['phone']);
        $this->assertDatabaseHas('Phones',[
            'contact_id'=>$contact->id,
            'phone'=> '01024068783'
        ]);
    }
    public function test_update_an_email()
    {
        $ph = Phone::factory()->create();
        $response = $this->patch(route('phone.update',$ph->id),[
            'phone'=>'01024068783'
        ])
            ->assertOk()
            ->json();
        $this->assertEquals($ph['contact_id'],$response['contact_id']);
        $this->assertDatabaseHas('phones',['phone'=>'01024068783']);

    }
    public function test_delete_an_email()
    {
        // create it in DB
        $createdPhone = Phone::factory()->create(['contact_id'=>$this->createContact()->id]);
        // deleteJson it
        $response = $this->deleteJson(route('phone.destroy',[
            'phone'=> $createdPhone->id
        ]/* , if you need to pass url parameter*/) /* ,pass Body */ );
        // scan status code
        $response->assertNoContent();//  assertStatusCode(204)
        // scan emptyResponse
        // scan missing in DB
        $this->assertDatabaseMissing('phones',['id'=> $createdPhone->id]);

    }
}
