<?php

namespace Omnipay\Coinbase\Message;

use Omnipay\Common\Message\RedirectResponseInterface;

/**
 * Coinbase Purchase Response
 */
class PurchaseResponse extends Response implements RedirectResponseInterface
{
    const API_VERSION = 'v2';

    protected $redirectLiveEndpoint = 'https://api.coinbase.com';
    protected $redirectTestEndpoint = 'https://api.sandbox.coinbase.com';

    public function isSuccessful()
    {
        return false;
    }

    public function isRedirect()
    {
        return isset($this->data['success']) && $this->data['success'];
    }

    public function getRedirectMethod()
    {
        return 'GET';
    }

    public function getRedirectUrl()
    {
        if ($this->isRedirect()) {
            return $this->getCheckoutEndpoint().'/'.$this->getTransactionReference();
        }
    }

    public function getRedirectData()
    {
        return null;
    }

    public function getTransactionReference()
    {
        if (isset($this->data['button']['code'])) {
            return $this->data['button']['code'];
        }
    }

    protected function getCheckoutEndpoint()
    {
        $base = $this->getRequest()->getTestMode() ? $this->redirectTestEndpoint : $this->redirectLiveEndpoint;
        return $base . '/' . self::API_VERSION . '/checkouts';
    }
}
