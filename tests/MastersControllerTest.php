<?php
/**
 * Created by PhpStorm.
 * User: beren
 * Date: 04/09/2018
 * Time: 18:56
 */

namespace App\Tests;


use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class MastersControllerTest extends WebTestCase
{
    public function testAdminGetMastersAll(){
        $client = static::createClient();
        $client->request(
            'GET',
            '/api/masters',
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
        $this->assertCount(10, $arrayContent);
    }

    public function testUserGetMastersAll(){
        $client = static::createClient();
        $client->request(
            'GET',
            '/api/masters',
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

    public function testAnonymousGetMastersAll(){
        $client = static::createClient();
        $client->request('GET','/api/masters');

        $response = $client->getResponse();
        $content =$response->getContent();

        $this->assertEquals(401, $response->getStatusCode());
        $this->assertJson($content);

        $arrayContent = json_decode($content, true);

    }

    public function testAdminGetMastersOne(){
        $client = static::createClient();
        $client->request(
            'GET',
            '/api/masters/1',
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

    public function testUserGetMastersOne(){
        $client = static::createClient();
        $client->request(
            'GET',
            '/api/masters/1',
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

    public function testAnonymousGetMastersOne(){
        $client = static::createClient();
        $client->request('GET','/api/masters/1');

        $response = $client->getResponse();
        $content =$response->getContent();

        $this->assertEquals(200, $response->getStatusCode());
        $this->assertJson($content);

        $arrayContent = json_decode($content, true);

    }

    public function testAdminPostMasters(){
        $data = [
            "firstname" => "Baptiste",
            "lastname"=> "Luluberlu",
            "email"=> "baptiste.luluberlu@msn.com"
        ];

        $client = static::createClient();
        $client->request(
            'POST',
            '/api/masters',
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

    public function testUserPostMasters(){
        $data = [
            "firstname" => "Roger",
            "lastname"=> "Letavernier",
            "email"=> "roger@gmail.com"
        ];

        $client = static::createClient();
        $client->request(
            'POST',
            '/api/masters',
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

    public function testAnonymousPostMasters(){
        $data = [
            "firstname" => "Jean-Claude",
            "lastname"=> "Duchemin",
            "email" => "JC.duchemin@muller.org"
        ];

        $client = static::createClient();
        $client->request(
            'POST',
            '/api/masters',
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
            '/api/masters',
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

    public function testAdminPutMasters(){
        $data = [
            "firstname" => "Testu"
        ];

        $client = static::createClient();
        $client->request(
            'PUT',
            '/api/masters/2',
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

    public function testAdminDeleteMasters(){
        $client = static::createClient();
        $client->request(
            'DELETE',
            '/api/masters/11',
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

    public function testUserDeleteMasters(){
        $client = static::createClient();
        $client->request(
            'DELETE',
            '/api/masters/12',
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

        dump($arrayContent = json_decode($content, true));

        $client = static::createClient();
        $client->request(
            'DELETE',
            '/api/masters/2',
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

        dump($arrayContent = json_decode($content, true));

    }

    public function testAnonymousDeleteMasters(){
        $client = static::createClient();
        $client->request('DELETE','/api/masters/13');

        $response = $client->getResponse();
        $content =$response->getContent();

        $this->assertEquals(401, $response->getStatusCode());
        $this->assertJson($content);

    }
}