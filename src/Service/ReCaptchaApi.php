<?php

namespace App\Service;

use Symfony\Component\HttpClient\HttpClient;

class ReCaptchaApi
{
    public function verifySite($userResponse)
    {
        $client = HttpClient::create();
        $response = $client->request(
            'POST',
            'https://www.google.com/recaptcha/api/siteverify?secret=XXX&response=' . $userResponse
        );
        
        $statusCode = $response->getStatusCode(); // get Response status code 200

        if ($statusCode === 200) {
            $apiResponse = $response->getContent();

            $apiResponse = $response->toArray();
            // convert the response (here in JSON) to a PHP array
            
            return $apiResponse;
        }
    }

}
