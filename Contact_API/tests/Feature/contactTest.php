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
            ->assertOk()
            ->json();

        $this->assertEquals($nconatct , count($response['data']));
        $this->assertEquals($this->createdContact->title,$response['data'][0]['title']);
    }
    public function test_fetch_one_contact(): void
    {
        $local_generated_contact = $this->createContact(['title'=>'dummy title']);

        $response = $this->getJson(route('contact.show',$this->createdContact->id))
            ->assertOk()->json();
        $this->assertCount(1, $response);
        $this->assertEquals($this->createdContact->title,$response['data']['title']);
    }
    public function test_fetch_non_existent_contact(): void
    {
        $this->getJson(route('contact.show', 999))
            ->assertNotFound();
    }


    public function test_store_contact_with_all_attributes(): void
    {

        $response =  $this->postJson(route('contact.store'),[
            'title'=> 'contact Test Title',
            'phones'=>[
                '01024068783',
                '01069345895'
            ],
            'emails'=>[
                'my@gmail.com',
                'he@yahoo.com',
            ]
        ])->assertCreated()->json();
        $response = $response['data'];
        //$this->assertEquals($local_cont->id, $response['id']);
        $this->assertEquals('contact Test Title', $response['title']);

        $this->assertDatabaseHas('Contacts',['title'=>'contact Test Title']);

        // email
        $this->assertEquals('my@gmail.com', $response['emails'][0]['email']);
        $this->assertEquals('he@yahoo.com', $response['emails'][1]['email']);
        $this->assertDatabaseHas('Contacts',['title'=>'contact Test Title']);
        $this->assertDatabaseHas('emails',[
            'contact_id'=>$response['id'],
            'email'=>'my@gmail.com'
        ]);
        $this->assertDatabaseHas('emails',[
            'contact_id'=>$response['id'],
            'email'=>'he@yahoo.com'
        ]);

        // phone
        $this->assertEquals('01024068783', $response['phones'][0]['phone']);
        $this->assertEquals('01069345895', $response['phones'][1]['phone']);
        $this->assertDatabaseHas('Contacts',['title'=>'contact Test Title']);
        $this->assertDatabaseHas('phones',[
            'contact_id'=>$response['id'],
            'phone'=>'01024068783'
        ]);
        $this->assertDatabaseHas('phones',[
            'contact_id'=>$response['id'],
            'phone'=>'01069345895'
        ]);
    }
    public function test_store_contact_with_missing_body(): void
    {
        $response = $this->postJson(route('contact.store'))
            ->assertUnprocessable()
            ->assertJsonValidationErrors(['phones','title','emails'])
            ->json();
//        dd($response);
        $this->assertEquals(1,DB::table('Contacts')->count());

    }
    public function test_store_contact_With_invalid_phone(): void
    {
        $this->postJson(route('contact.store'), [
            'title'=> 'contact Test Title',
            'phones'=>[
                'invalid',
            ],
            'emails'=>[
            ]
        ])
            ->assertUnprocessable()
            ->assertJsonValidationErrors(['phones.0']);
    }
    public function test_store_contact_With_invalid_email(): void
    {
        // TODO
        //  ASK if one valid and one invalid
        $this->postJson(route('contact.store'), [
            'title'=> 'contact Test Title',
            'phones'=>[
                '01024068783'
            ],
            'emails'=>[
                'mygmail',
            ]
        ])
            ->assertUnprocessable()
            ->assertJsonValidationErrors(['emails.0']);
    }
    public function test_store_contact_With_duplicatePhone(): void
    {

        $response = $this->postJson(route('contact.store'), [
            'title'=> 'contact Test Title',
            'phones'=>[
                '01024068783',
                '01024068783'
            ],
            'emails'=>[
            ]
        ])->assertUnprocessable()
            ->assertJsonValidationErrors(['phones.1']);
    }
    public function test_store_contact_With_duplicateEmail(): void
    {
        $this->postJson(route('contact.store'), [
            'title'=> 'contact Test Title',
            'phones'=>[
                '01024068783',
                '01069345895'
            ],
            'emails'=>[
                'my@gmail.com',
                'my@gmail.com',
            ]
        ])->assertUnprocessable()
            ->assertJsonValidationErrors(['emails.0','emails.1']);
    }
    public function test_store_contact_with_phone_without_email():void
    {
        $response = $this->postJson(route('contact.store'),[
            'title'=> 'contact Test Title',
            'phones'=>[
                '01024068783',
                '01069345895'
            ],
            'emails'=>[
            ]
        ])->assertCreated()->json();
        $response = $response['data'];
        $this->assertEquals('01024068783', $response['phones'][0]['phone']);
        $this->assertEquals('01069345895', $response['phones'][1]['phone']);
        $this->assertDatabaseHas('Contacts',['title'=>'contact Test Title']);
        $this->assertDatabaseHas('phones',[
            'contact_id'=>$response['id'],
            'phone'=>'01024068783'
        ]);
        $this->assertDatabaseHas('phones',[
            'contact_id'=>$response['id'],
            'phone'=>'01069345895'
        ]);


    }
    public function test_store_contact_with_email_without_phone():void
    {
        $response = $this->postJson(route('contact.store'),[
            'title'=> 'contact Test Title',
            'phones'=>[
            ],
            'emails'=>[
                'my@email.com',
                'he@yahoo.com'
            ]
        ])->assertCreated()->json();
        $response = $response['data'];
        $this->assertEquals('my@email.com', $response['emails'][0]['email']);
        $this->assertEquals('he@yahoo.com', $response['emails'][1]['email']);
        $this->assertDatabaseHas('Contacts',['title'=>'contact Test Title']);
        $this->assertDatabaseHas('emails',[
            'contact_id'=>$response['id'],
            'email'=>'my@email.com'
        ]);
        $this->assertDatabaseHas('emails',[
            'contact_id'=>$response['id'],
            'email'=>'he@yahoo.com'
        ]);


    }
    public function test_store_contact_without_email_and_without_phone():void
    {
        $response = $this->postJson(route('contact.store'),[
            'title'=>'fake title',
        ])->assertUnprocessable();

        $this->assertDatabaseMissing('Contacts',['title'=>'fake title']);
    }


    public function test_update_contact(): void
    {
        $response = $this->patchJson(route('contact.update',$this->createdContact->id),[
            'title'=>'my new updated Title'
        ]);
        $response->assertOk()->json();
        $response = $response['data'];
        $this->assertEquals($response['title'],'my new updated Title');
        //dd($response->json());
        //$this->assertEquals($response['title'=>'my new updated Title'])
        $this->assertDatabaseHas('contacts',[
            'title'=>'my new updated Title'
        ]);


    }
    public function test_update_non_existent_contact(): void
    {
        $this->patchJson(route('contact.update', 999), ['title' => 'new title'])
            ->assertNotFound();
    }

//    public function test_update_contact_with_invalid_phone(): void
//    {
//        $response = $this->patchJson(route('contact.update', $this->createdContact->id),
//            [
//                'title'=>'my test title',
//                'phone'=>'invalid',
//                'email'=>'my@gmail.com'
//            ])
//            ->assertUnprocessable()
//            ->assertJsonValidationErrors(['phone']);
//    }
//    public function test_update_contact_with_invalid_email(): void
//    {
//        $response = $this->patchJson(route('contact.update', $this->createdContact->id),
//            [
//                'title'=>'my test title',
//                'phone'=>'01024068783',
//                'email'=>'my@'
//            ])
//            ->assertUnprocessable()
//            ->assertJsonValidationErrors(['email']);
//    }


    public function test_delete_contact(): void
    {
        // we already has a record in DB in setUp function
        $response = $this->deleteJson(route('contact.destroy',$this->createdContact->id));
        $response->assertNoContent();
        $this->assertDatabaseMissing('Contacts',['title'=>$this->createdContact->title]);

    }
    public function test_delete_non_existent_contact(): void
    {
        $this->deleteJson(route('contact.destroy', 999))
            ->assertNotFound();
    }
}
