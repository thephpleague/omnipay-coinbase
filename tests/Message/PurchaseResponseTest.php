<?php

namespace Omnipay\Coinbase\Message;

use Omnipay\Tests\TestCase;

class PurchaseResponseTest extends TestCase
{
    public function testSuccess()
    {
        $httpResponse = $this->getMockHttpResponse('PurchaseSuccess.txt');
        $request = $this->getMockRequest();
        $request->shouldReceive('getTestMode')->once()->andReturn(false);
        $response = new PurchaseResponse($request, $httpResponse->json());

        $this->assertFalse($response->isSuccessful());
        $this->assertTrue($response->isRedirect());
        $this->assertNull($response->getMessage());
        $this->assertSame('GET', $response->getRedirectMethod());
        $this->assertSame('https://api.coinbase.com/v2/checkouts/30dae91b81299066ba126e3858f89fd8', $response->getRedirectUrl());
        $this->assertNull($response->getRedirectData());
        $this->assertSame('30dae91b81299066ba126e3858f89fd8', $response->getTransactionReference());
    }

    public function testFailure()
    {
        $httpResponse = $this->getMockHttpResponse('PurchaseFailure.txt');
        $response = new PurchaseResponse($this->getMockRequest(), $httpResponse->json());

        $this->assertFalse($response->isSuccessful());
        $this->assertFalse($response->isRedirect());
        $this->assertSame("Name can't be blank", $response->getMessage());
        $this->assertNull($response->getRedirectUrl());
        $this->assertNull($response->getRedirectData());
        $this->assertSame('c777f2ca6e01b8c116b267a053603e62', $response->getTransactionReference());
    }

    public function testEmpty()
    {
        $response = new PurchaseResponse($this->getMockRequest(), array());

        $this->assertFalse($response->isSuccessful());
        $this->assertFalse($response->isRedirect());
        $this->assertNull($response->getTransactionReference());
        $this->assertNull($response->getMessage());
    }
}
