<?php
namespace Payum\Klarna\Invoice\Action\Api;

use Payum\Core\Action\ActionInterface;
use Payum\Core\ApiAwareInterface;
use Payum\Core\Exception\UnsupportedApiException;
use Payum\Klarna\Invoice\Config;

abstract class BaseApiAwareAction implements  ApiAwareInterface, ActionInterface
{
    /**
     * @var Config
     */
    protected $config;

    /**
     * @param mixed $api
     *
     * @throws UnsupportedApiException if the given Api is not supported.
     */
    public function setApi($api)
    {
        if (false == $api instanceof Config) {
            throw new UnsupportedApiException('Instance of Config is expected to be passed as api.');
        }

        $this->config = $api;
    }

    /**
     * @return \Klarna
     */
    protected function createKlarna()
    {
        $klarna = new \Klarna;

        $klarna->config(
            $this->config->eid,
            $this->config->secret,
            $this->config->country,
            $this->config->language,
            $this->config->currency,
            $this->config->testMode
        );

        return $klarna;
    }
}
