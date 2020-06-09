<?php

namespace ByTIC\MFinante\Scrapers\Traits;

use ByTIC\GouttePhantomJs\Clients\ClientFactory;
use ByTIC\GouttePhantomJs\Clients\PhantomJs\ClientBridge;
use ByTIC\MFinante\Session\CookieJar;
use Goutte\Client;

/**
 * Trait HasClient
 * @package ByTIC\MFinante\Scrapers\Traits
 */
trait HasClient
{

    /**
     * @var Client
     */
    protected $client = null;

    /**
     * @return Client|ClientBridge
     */
    public function getClient()
    {
        if ($this->client == null) {
            $this->initClient();
        }

        return $this->client;
    }

    protected function initClient()
    {
        $client = $this->generateClient();
        $this->setClient($client);
    }

    /**
     * @param Client $client
     */
    public function setClient($client)
    {
        $this->client = $client;
    }

    /**
     * @return Client
     */
    protected function generateClient()
    {
        $phantomJsClient = new \ByTIC\GouttePhantomJs\Clients\PhantomJs\ClientBridge();
        return ClientFactory::getPhantomJsClient(null, CookieJar::instance());
    }
}