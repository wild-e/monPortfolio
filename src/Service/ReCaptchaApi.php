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
            'https://www.google.com/recaptcha/api/siteverify?secret=6LdNyUgaAAAAAKuL5c7Iejq7Q1Tcwa86bPI2z-A6&response=' . $userResponse
        );
        
        $statusCode = $response->getStatusCode(); // get Response status code 200

        if ($statusCode === 200) {
            $apiResponse = $response->getContent();
            // get the response in JSON format

            $apiResponse = $response->toArray();
            // convert the response (here in JSON) to an PHP array
            
            return $apiResponse;
        }
    }

}