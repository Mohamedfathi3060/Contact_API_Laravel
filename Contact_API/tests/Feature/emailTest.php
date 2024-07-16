<?php

namespace Tests\Feature;

use App\Models\Contact;
use App\Models\Email;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class emailTest extends TestCase
{
    use RefreshDatabase;
    public function test_fetch_all_emails_of_contact()
    {
        $contact = Contact::factory()->create(['title'=>'ex']);
        $em1 = Email::factory()->create(['contact_id'=>$contact->id]);
        $em2 = Email::factory()->create(['contact_id'=>$contact->id,'email'=>'admin@gmail.com']);
        $response = $this->getJson(route('contact.email.index',$contact->id))
            ->assertOk()->json();
        $this->assertEquals($em1->email , $response[0]['email']);
        $this->assertEquals($em2->email , $response[1]['email']);

    }
    public function test_store_an_email_for_a_contact()
    {
        $contact = Contact::factory()->create(['title'=>'ex']);
        // $em1 = Email::factory()->create(['contact_id'=>$contact->id]);
        // $em2 = Email::factory()->create(['contact_id'=>$contact->id,'email'=>'admin@gmail.com']);
        $response = $this->postJson(route('contact.email.store', $contact->id),[
            'email' => 'news@gmail.com'
        ])
            ->assertCreated()->json();
        $this->assertEquals('news@gmail.com' , $response['emails'][0]['email']);
        $this->assertDatabaseHas('Emails',[
            'contact_id'=>$contact->id,
            'email'=> 'news@gmail.com'
        ]);
    }
    public function test_update_an_email()
    {
        $em = Email::factory()->create();
        $response = $this->patch(route('email.update',$em->id),[
            'email'=>'moha123@yahoo.com'
        ])
            ->assertOk()
            ->json();
        $this->assertEquals($em['contact_id'],$response['contact_id']);
        $this->assertDatabaseHas('emails',['email'=>'moha123@yahoo.com']);

    }
    public function test_delete_an_email()
    {
        // create it in DB
        $createdEmail = Email::factory()->create(['contact_id'=>$this->createContact()->id]);
        // deleteJson it
        $response = $this->deleteJson(route('email.destroy',[
            'email'=> $createdEmail->id
        ]/* , if you need to pass url parameter*/) /* ,pass Body */ );
        // scan status code
        $response->assertNoContent();//  assertStatusCode(204)
        // scan emptyResponse
        // scan missing in DB
        $this->assertDatabaseMissing('emails',['id'=> $createdEmail->id]);

    }
}
