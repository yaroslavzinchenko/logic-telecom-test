<?php declare(strict_types=1);

use GuzzleHttp\Client;
use PHPUnit\Framework\TestCase;

final class Test extends TestCase
{
    public function testCheckIsAttached1()
    {
        $base_uri = 'http://localhost/documents/check-attach';
        $client = new Client(['base_uri' => $base_uri]);
        $response = $client->request('POST', $base_uri, [
            'form_params' => [
                'token' => 'd363103e44ea7d2b108b11c6ea5ce31e',
                'document_id' => 3,
                'actor_id' => 1
            ]
        ]);

        $this->assertEquals(200, $response->getStatusCode());
        $this->assertEquals('Attached.', $response->getBody());
    }

    public function testCheckIsAttached2()
    {
        $base_uri = 'http://localhost/documents/check-attach';
        $client = new Client(['base_uri' => $base_uri]);
        $response = $client->request('POST', $base_uri, [
            'form_params' => [
                'token' => 'd363103e44ea7d2b108b11c6ea5ce31e',
                'document_id' => 4,
                'actor_id' => 1
            ]
        ]);

        $this->assertEquals(200, $response->getStatusCode());
        $this->assertEquals('Attached.', $response->getBody());
    }

    public function testCheckChangeStatus1()
    {
        $base_uri = 'http://localhost/documents/change-status';
        $client = new Client(['base_uri' => $base_uri]);
        $response = $client->request('POST', $base_uri, [
            'form_params' => [
                'token' => 'd363103e44ea7d2b108b11c6ea5ce31e',
                'document_id' => 1,
                'actor_id' => 1,
                'status_id' => 2,
            ]
        ]);

        $this->assertEquals(200, $response->getStatusCode());
        $this->assertEquals('Changed.', $response->getBody());
    }

    public function testCheckChangeStatus2()
    {
        $base_uri = 'http://localhost/documents/change-status';
        $client = new Client(['base_uri' => $base_uri]);
        $response = $client->request('POST', $base_uri, [
            'form_params' => [
                'token' => 'd363103e44ea7d2b108b11c6ea5ce31e',
                'document_id' => 1,
                'actor_id' => 1,
                'status_id' => 1,
            ]
        ]);

        $this->assertEquals(200, $response->getStatusCode());
        $this->assertEquals('Changed.', $response->getBody());
    }
}
