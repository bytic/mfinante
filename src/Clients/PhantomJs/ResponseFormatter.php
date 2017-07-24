<?php

namespace ByTIC\MFinante\Clients\PhantomJs;

use GuzzleHttp\Psr7\Response;

/**
 * Class ResponseBridge
 * @package ByTIC\MFinante\Clients\PhantomJs
 */
class ResponseFormatter extends Response
{

    /**
     * ResponseBridge constructor.
     * @param \JonnyW\PhantomJs\Http\Response|\JonnyW\PhantomJs\Http\ResponseInterface $phantomJsResponse
     * @return Response
     */
    public static function format($phantomJsResponse)
    {
        $response = new Response(
            $phantomJsResponse->getStatus(),
            $phantomJsResponse->getHeaders(),
            $phantomJsResponse->getContent()
        );
        return $response;
    }
}
