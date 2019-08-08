<?php

declare(strict_types=1);

namespace App\Handler;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Zend\Diactoros\Response\JsonResponse;

class GmapiHandler implements RequestHandlerInterface
{
    public function handle(ServerRequestInterface $request) : ResponseInterface
    {
        // Received lat lng from Front-End
        $rq = json_decode($request->getBody()->getContents(), true);
        $lat = $rq['data']['lat'];
        $lng = $rq['data']['lng'];

        // A Nearby Search request is an HTTP URL of Google Map APIs
        $url = 'https://maps.googleapis.com/maps/api/place/nearbysearch/json?location='.$lat.','.$lng.'&radius=1500&type=restaurant&key=AIzaSyAnxzrMjUAJw-1ShWUSMqvMKuB0KWvVRjE';

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_PROXYPORT, 3128);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
        $response = curl_exec($ch);
        curl_close($ch);

        // Prepare data
        $response = json_decode($response);

        // Return to Back-End
        return new JsonResponse(['result' => $response]);
    }
}
