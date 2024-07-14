<?php

namespace Tests\Feature;

use App\Models\Contact;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

class contactTest extends TestCase
{
    use RefreshDatabase;
    public $createdContact;
    public function setUp(): void
    {
        parent::setUp();
        $this->createdContact = $this->createContact();
    }

    public function test_fetch_all_contacts(): void
    {
        // prepare
        $nconatct = DB::table('Contacts')->count();
        // perform
        // assert
        $response = $this->getJson(route('contact.index'))
            ->assertOk();
        $this->assertEquals($nconatct , count($response->json()));
        $this->assertEquals($this->createdContact->phone,$response[0]['phone']);

    }
    public function test_fetch_one_contact(): void
    {
        $local_generated_contact = $this->createContact();
        // prepare
        // perform
        // assert
        $response = $this->getJson(route('contact.show',$this->createdContact->id))
            ->assertOk()->json();
//        $this->assertCount(1, $response);
        $this->assertEquals($this->createdContact->phone, $response['phone']);

    }
    public function test_fetch_non_existent_contact(): void
    {
        $this->getJson(route('contact.show', 999))
            ->assertNotFound();
    }

    public function test_store_contact_with_all_attributes(): void
    {
        $local_cont = Contact::factory()->make([
            'title'=>'my test title',
            'phone'=>'01024068783',
            'email'=>'my@gmail.com'
        ]);
        $response =  $this->postJson(route('contact.store'),[
            'title'=>$local_cont->title,
            'email'=>$local_cont->email,
            'phone'=>$local_cont->phone,
        ])->assertCreated()->json();
        $this->assertEquals($local_cont->phone, $response['phone']);
        $this->assertDatabaseHas('Contacts',['phone'=>$local_cont->phone]);
    }
    public function test_store_contact_with_missing_body(): void
    {
        $this->postJson(route('contact.store'))
            ->assertUnprocessable()
            ->assertJsonValidationErrors(['phone','title'])
            ->json();
        $this->assertEquals(1,DB::table('Contacts')->count());

    }
    public function test_store_contact_With_invalid_phone(): void
    {
        $this->postJson(route('contact.store'), [
            'title'=>'my test title',
            'phone'=>'invalid',
            'email'=>'my@gmail.com'
        ])
            ->assertUnprocessable()
            ->assertJsonValidationErrors(['phone']);
    }
    public function test_store_contact_With_invalid_email(): void
    {
        $this->postJson(route('contact.store'), [
            'title'=>'my test title',
            'phone'=>'01024068783',
            'email'=>'my'
        ])
            ->assertUnprocessable()
            ->assertJsonValidationErrors(['email']);
    }
    public function test_store_contact_With_duplicatePhone(): void
    {
        $this->postJson(route('contact.store'), [
            'title' => 'Test',
            'phone' => $this->createdContact->phone,
            'email' => 'test@example.com',
        ])->assertUnprocessable()
            ->assertJsonValidationErrors(['phone']);
    }
    public function test_store_contact_With_duplicateEmail(): void
    {
        $this->postJson(route('contact.store'), [
            'title' => 'Test',
            'phone' => '123456789',
            'email' => $this->createdContact->email,
        ])->assertUnprocessable()
            ->assertJsonValidationErrors(['email']);
    }


    public function test_update_contact(): void
    {
        $response = $this->patchJson(route('contact.update',$this->createdContact->id),[
            'title'=>'my new updated Title'
        ]);
        $response->assertOk();
        $this->assertEquals($response['title'],'my new updated Title');
        //dd($response->json());
        //$this->assertEquals($response['title'=>'my new updated Title'])
        $this->assertDatabaseHas('contacts',[
            'title'=>'my new updated Title'
        ]);


    }
    public function test_update_contact_with_invalid_phone(): void
    {
        $response = $this->patchJson(route('contact.update', $this->createdContact->id),
            [
                'title'=>'my test title',
                'phone'=>'invalid',
                'email'=>'my@gmail.com'
            ])
            ->assertUnprocessable()
            ->assertJsonValidationErrors(['phone']);
    }
    public function test_update_contact_with_invalid_email(): void
    {
        $response = $this->patchJson(route('contact.update', $this->createdContact->id),
            [
                'title'=>'my test title',
                'phone'=>'01024068783',
                'email'=>'my@'
            ])
            ->assertUnprocessable()
            ->assertJsonValidationErrors(['email']);
    }
    public function testUpdateNonExistentContact(): void
    {
        $this->patchJson(route('contact.update', 999), ['title' => 'new title'])
            ->assertNotFound();
    }

    public function test_delete_contact(): void
    {
        // we already has a record in DB in setUp function
        $response = $this->deleteJson(route('contact.destroy',$this->createdContact->id));
        $response->assertNoContent();
        $this->assertDatabaseMissing('Contacts',['phone'=>$this->createdContact->phone]);

    }
    public function test_delete_non_existent_contact(): void
    {
        $this->deleteJson(route('contact.destroy', 999))
            ->assertNotFound();
    }
}
