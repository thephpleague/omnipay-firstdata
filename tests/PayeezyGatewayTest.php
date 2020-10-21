<?php

namespace Omnipay\FirstData;

use Omnipay\Common\CreditCard;
use Omnipay\Common\Exception\InvalidCreditCardException;
use Omnipay\Common\Exception\InvalidRequestException;
use Omnipay\Tests\GatewayTestCase;

class PayeezyGatewayTest extends GatewayTestCase
{
    /** @var  PayeezyGateway */
    protected $gateway;

    /** @var  array */
    protected $options;

    public function setUp()
    {
        parent::setUp();

        $this->gateway = new PayeezyGateway($this->getHttpClient(), $this->getHttpRequest());
        $this->gateway->setGatewayId('1234');
        $this->gateway->setPassword('abcde');

        $this->options = array(
            'amount' => '13.00',
            'card' => $this->getValidCard(),
            'transactionId' => 'order2',
            'currency' => 'USD',
            'testMode' => true,
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
        $this->assertEquals('ET181147::28513493', $response->getTransactionReference());
        $this->assertEquals('000056', $response->getSequenceNo());
        $this->assertEmpty($response->getCardReference());
    }

    public function testAuthorizeSuccess()
    {
        $this->setMockHttpResponse('PurchaseSuccess.txt');

        $response = $this->gateway->authorize($this->options)->send();

        $this->assertTrue($response->isSuccessful());
        $this->assertFalse($response->isRedirect());
        $this->assertEquals('ET181147::28513493', $response->getTransactionReference());
    }

    public function testPurchaseFailureMissingAmount(){

        $this->expectException(InvalidRequestException::class);

        unset($this->options['amount']);
        $response = $this->gateway->purchase($this->options)->send();
    }

    public function testPurchaseFailureInvalidCard(){
        $this->expectException(InvalidCreditCardException::class);
        $this->options['card'] = new CreditCard([
            'firstName'            => 'Example',
            'lastName'             => 'Customer',
            'expiryMonth'          => '12',
            'expiryYear'           => '2019',
            'cvv'                  => '123',
        ]);
        $response = $this->gateway->purchase($this->options)->send();
    }

}
