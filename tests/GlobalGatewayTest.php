<?php

namespace Omnipay\FirstData;

use Omnipay\Tests\GatewayTestCase;

class GlobalGatewayTest extends GatewayTestCase
{
    public function setUp()
    {
        parent::setUp();

        $this->gateway = new GlobalGateway($this->getHttpClient(), $this->getHttpRequest());
        $this->gateway->setGatewayId('1234');
        $this->gateway->setPassword('abcde');

        $this->options = array(
            'amount' => '13.00',
            'card' => $this->getValidCard(),
            'transactionId' => 'order2',
            'currency' => 'USD',
            'testMode' => true,
        );

        $this->refundOptions = array(
            'amount' => 13.00,
            'transactionReference' => '28513493',
            'authorizationCode' => 'ET181147'
        );
    }

    public function testProperties()
    {
        $this->assertEquals('1234', $this->gateway->getGatewayId());
        $this->assertEquals('abcde', $this->gateway->getPassword());
    }

    public function testPurchaseSuccess()
    {
        $this->setMockHttpResponse('PurchaseSuccess.txt');

        $response = $this->gateway->purchase($this->options)->send();

        $this->assertTrue($response->isSuccessful());
        $this->assertFalse($response->isRedirect());
        $this->assertEquals('28513493', $response->getTransactionReference());
        $this->assertEquals('ET181147', $response->getAuthorizationCode());
    }

    public function testAuthorizeSuccess()
    {
        $this->setMockHttpResponse('PurchaseSuccess.txt');

        $response = $this->gateway->authorize($this->options)->send();

        $this->assertTrue($response->isSuccessful());
        $this->assertFalse($response->isRedirect());
        $this->assertEquals('28513493', $response->getTransactionReference());
        $this->assertEquals('ET181147', $response->getAuthorizationCode());
    }


    public function testRefundSuccess()
    {
        $this->setMockHttpResponse('RefundSuccess.txt');

        $response = $this->gateway->refund($this->refundOptions)->send();

        $this->assertTrue($response->isSuccessful());
        $this->assertFalse($response->isRedirect());
        $this->assertEquals('28513493', $response->getTransactionReference());
        $this->assertEquals('ET181147', $response->getAuthorizationCode());
    }
}
