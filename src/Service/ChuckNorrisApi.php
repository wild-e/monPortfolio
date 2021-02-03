<?php

namespace App\Service;

use Symfony\Component\HttpClient\HttpClient;

class ChuckNorrisApi
{
    public function randomJoke()
    {
        $client = HttpClient::create();
        $response = $client->request(
            'GET',
            'https://api.chucknorris.io/jokes/random'
        );
        
        $statusCode = $response->getStatusCode(); // get Response status code 200

        if ($statusCode === 200) {
            $content = $response->getContent();
            // get the response in JSON format

            $content = $response->toArray();
            // convert the response (here in JSON) to an PHP array
            
            return $content;
        }
    }

}