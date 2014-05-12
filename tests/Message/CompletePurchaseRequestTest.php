<?php

namespace Omnipay\Coinbase\Message;

use Omnipay\Tests\TestCase;

class CompletePurchaseRequestTest extends TestCase
{
    public function setUp()
    {
        $this->httpRequest = $this->getHttpRequest();
        $this->request = new CompletePurchaseRequest($this->getHttpClient(), $this->httpRequest);
        $this->request->initialize(
            array(
                'apiKey' => 'abc123',
                'secret' => 'shhh',
            )
        );
    }

    public function testGetDataGet()
    {
        $this->httpRequest->query->replace(
            array('order' => array('id' => '9XMWP4YG'))
        );

        $data = $this->request->getData();
        $this->assertSame('9XMWP4YG', $data['id']);
    }

    public function testGetDataPost()
    {
        $this->httpRequest->request->replace(
            array('order' => array('id' => '9XMWP4YG'))
        );

        $data = $this->request->getData();
        $this->assertSame('9XMWP4YG', $data['id']);
    }

    /**
     * @expectedException \Omnipay\Common\Exception\InvalidRequestException
     * @expectedExceptionMessage Missing Order ID
     */
    public function testGetDataInvalid()
    {
        $this->request->getData();
    }

    public function testSendSuccess()
    {
        $this->httpRequest->request->replace(
            array('order' => array('id' => '9XMWP4YG'))
        );
        $this->setMockHttpResponse('FetchTransactionSuccess.txt');
        $response = $this->request->send();

        $this->assertTrue($response->isSuccessful());
        $this->assertFalse($response->isRedirect());
        $this->assertSame('completed', $response->getMessage());
        $this->assertSame('9XMWP4YG', $response->getTransactionReference());
    }

    public function testSendFailure()
    {
        $this->httpRequest->request->replace(
            array('order' => array('id' => '9XMWP4YG'))
        );
        $this->setMockHttpResponse('FetchTransactionFailure.txt');
        $response = $this->request->send();

        $this->assertFalse($response->isSuccessful());
        $this->assertFalse($response->isRedirect());
        $this->assertSame('Order not found with that id', $response->getMessage());
        $this->assertNull($response->getTransactionReference());
    }
}
