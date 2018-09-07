<?php
/**
 * Created by PhpStorm.
 * User: beren
 * Date: 07/09/2018
 * Time: 18:48
 */

namespace App\Tests;


use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class CreditcardsControllerTest extends WebTestCase
{

    public function testAdminGetCreditcardsAll(){
        $client = static::createClient();
        $client->request(
            'GET',
            '/api/creditcards',
            [],
            [],
            [
                'CONTENT_TYPE' => 'application/json',
                'HTTP_AUTH-TOKEN' => '5b7fd37c2995a9.89857955',
            ]
        );

        $response = $client->getResponse();
        $content =$response->getContent();

        $this->assertEquals(200, $response->getStatusCode());
        $this->assertJson($content);

    }

    public function testUserGetCreditcardsAll(){
        $client = static::createClient();
        $client->request(
            'GET',
            '/api/creditcards',
            [],
            [],
            [
                'CONTENT_TYPE' => 'application/json',
                'HTTP_AUTH-TOKEN' => '5b7fd37c29d9f1.52897532',
            ]
        );

        $response = $client->getResponse();
        $content =$response->getContent();

        $this->assertEquals(403, $response->getStatusCode());
        $this->assertJson($content);

        $arrayContent = json_decode($content, true);

    }

    public function testAnonymousGetCreditcardsAll(){
        $client = static::createClient();
        $client->request('GET','/api/creditcards');

        $response = $client->getResponse();
        $content =$response->getContent();

        $this->assertEquals(401, $response->getStatusCode());
        $this->assertJson($content);

        $arrayContent = json_decode($content, true);

    }

    public function testAdminGetCreditcardsOne(){
        $client = static::createClient();
        $client->request(
            'GET',
            '/api/creditcards/1',
            [],
            [],
            [
                'CONTENT_TYPE' => 'application/json',
                'HTTP_AUTH-TOKEN' => '5b7fd37c2995a9.89857955',
            ]
        );

        $response = $client->getResponse();
        $content =$response->getContent();

        $this->assertEquals(200, $response->getStatusCode());
        $this->assertJson($content);

        $arrayContent = json_decode($content, true);

    }

    public function testUserGetCreditcardsOne(){
        $client = static::createClient();
        $client->request(
            'GET',
            '/api/creditcards/1',
            [],
            [],
            [
                'CONTENT_TYPE' => 'application/json',
                'HTTP_AUTH-TOKEN' => '5b7fd37c29d9f1.52897532',
            ]
        );

        $response = $client->getResponse();
        $content =$response->getContent();

        $this->assertEquals(200, $response->getStatusCode());
        $this->assertJson($content);

        $arrayContent = json_decode($content, true);

    }

    public function testAnonymousGetCreditcardsOne(){
        $client = static::createClient();
        $client->request('GET','/api/creditcards/1');

        $response = $client->getResponse();
        $content =$response->getContent();

        $this->assertEquals(200, $response->getStatusCode());
        $this->assertJson($content);

        $arrayContent = json_decode($content, true);

    }

    public function testAdminPostCreditcards(){
        $data = [
            "name" => "My creditcard 11",
            "creditCardType"=> "Visa",
            "creditCardNumber"=> "1111111111111111",
            "company"=> [
                "id"=>1,
                "name"=> "Adams-Reichel"
            ]
        ];

        $client = static::createClient();
        $client->request(
            'POST',
            '/api/creditcards.json',
            [],
            [],
            [
                'CONTENT_TYPE' => 'application/json',
                'AUTH-TOKEN' => '5b7fd37c2995a9.89857955',
            ],
            json_encode($data)
        );

        $response = $client->getResponse();
        $content =$response->getContent();

        $this->assertEquals(201, $response->getStatusCode());
        $this->assertJson($content);

        $arrayContent = json_decode($content, true);

    }

    public function testUserPostCreditcards(){
        $data = [
            "name" => "My creditcard 12",
            "creditCardType"=> "Visa Retired",
            "creditCardNumber"=> "22222222222222",
            "company"=> ["id"=>1]
        ];

        $client = static::createClient();
        $client->request(
            'POST',
            '/api/creditcards',
            [],
            [],
            [
                'CONTENT_TYPE' => 'application/json',
                'AUTH-TOKEN' => '5b7fd37c29d9f1.52897532',
            ],
            json_encode($data)
        );

        $response = $client->getResponse();
        $content =$response->getContent();

        $this->assertEquals(201, $response->getStatusCode());
        $this->assertJson($content);

        $arrayContent = json_decode($content, true);
    }

    public function testAnonymousPostCreditcards(){
        $data = [
            "name" => "My creditcard 13",
            "creditCardType"=> "MasterCard",
            "creditCardNumber"=> "3333333333333333",
            "company"=> ["id"=>1]
        ];

        $client = static::createClient();
        $client->request(
            'POST',
            '/api/creditcards',
            [],
            [],
            [
                'CONTENT_TYPE' => 'application/json',
            ],
            json_encode($data)
        );

        $response = $client->getResponse();
        $content =$response->getContent();

        $this->assertEquals(201, $response->getStatusCode());
        $this->assertJson($content);

        $client = static::createClient();
        $client->request(
            'GET',
            '/api/creditcards',
            [],
            [],
            [
                'CONTENT_TYPE' => 'application/json',
                'HTTP_AUTH-TOKEN' => '5b7fd37c2995a9.89857955',
            ]
        );

        $response = $client->getResponse();
        $content =$response->getContent();

        $this->assertEquals(200, $response->getStatusCode());
        $this->assertJson($content);

        $arrayContent = json_decode($content, true);
        $this->assertCount(13, $arrayContent);
    }

    public function testAdminPutCreditcards(){
        $data = [
            "name" => "Put Company 1"
        ];

        $client = static::createClient();
        $client->request(
            'PUT',
            '/api/creditcards/2',
            [],
            [],
            [
                'CONTENT_TYPE' => 'application/json',
                'HTTP_AUTH-TOKEN' => '5b7fd37c2995a9.89857955',
            ],
            json_encode($data)
        );

        $response = $client->getResponse();
        $content =$response->getContent();

        $this->assertEquals(200, $response->getStatusCode());
        $this->assertJson($content);

        $arrayContent = json_decode($content, true);

    }

    public function testAdminDeleteCreditcards(){
        $client = static::createClient();
        $client->request(
            'DELETE',
            '/api/creditcards/11',
            [],
            [],
            [
                'CONTENT_TYPE' => 'application/json',
                'HTTP_AUTH-TOKEN' => '5b7fd37c2995a9.89857955',
            ]
        );

        $response = $client->getResponse();
        $content =$response->getContent();

        //Bug du test lors du renvoi de code 204 ==> envoi 200
        $this->assertEquals(200, $response->getStatusCode());
        $this->assertJson($content);
    }

    public function testUserDeleteAnotherCreditcards()
    {
        //Test to delete another company
        $client = static::createClient();
        $client->request(
            'DELETE',
            '/api/creditcards/5',
            [],
            [],
            [
                'CONTENT_TYPE' => 'application/json',
                'HTTP_AUTH-TOKEN' => '5b7fd37c29d9f1.52897532',
            ]
        );

        $response = $client->getResponse();
        $content = $response->getContent();

        $this->assertEquals(403, $response->getStatusCode());
        $this->assertJson($content);
    }


    public function testUserDeleteHisCreditcards(){
        //Test to delete his company
        $client = static::createClient();
        $client->request(
            'DELETE',
            '/api/creditcards/1',
            [],
            [],
            [
                'CONTENT_TYPE' => 'application/json',
                'HTTP_AUTH-TOKEN' => '5b7fd37c29d9f1.52897532',
            ]
        );

        $response = $client->getResponse();
        $content =$response->getContent();

        //Bug du test lors du renvoi de code 204 ==> envoi 200
        $this->assertEquals(200, $response->getStatusCode());
        $this->assertJson($content);

    }

    public function testAnonymousDeleteCreditcards(){
        $client = static::createClient();
        $client->request('DELETE','/api/creditcards/13');

        $response = $client->getResponse();
        $content =$response->getContent();

        $this->assertEquals(401, $response->getStatusCode());
        $this->assertJson($content);

    }

}