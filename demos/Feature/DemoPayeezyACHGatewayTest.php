<?php

namespace Omnipay\FirstData\Feature;

use Omnipay\Common\CreditCard;
use Omnipay\Common\Exception\InvalidRequestException;
use Omnipay\Common\Exception\InvalidResponseException;
use Omnipay\FirstData\Ach;
use Omnipay\Omnipay;
use Omnipay\Tests\GatewayTestCase;
use PHPUnit\Framework\TestCase;

class DemoPayeezyAchGateway extends TestCase
{
    public function setUp()
    {
        parent::setUp();
        $this->gateway = Omnipay::create('FirstData_Payeezy');
        $this->gateway->initialize([
            'gatewayId' => $_ENV['DEMO_GATEWAYID'],
            'password' => $_ENV['DEMO_PASSWORD'],
            'hmac' => $_ENV['DEMO_HMAC'],
            'keyId' => $_ENV['DEMO_KEYID'],
            'testMode'  => true,
        ]);

    }

    public function test_ach_purchase_with_address_and_cvv()
    {

 // * * checkType ->
 // * * accountNumber ->
 // * * routingNumber ->
 // * * checkNumber
 // * * customerIDType ->
 // * * License ->
 // * * LicenseState ->
 // * * ssn ->
 // * * taxId ->
 // * * militaryId ->
 // *
 // * * customerID ->
 // * * ecommerceFlag
 // * * releaseType ->
 // * * vip ->
 // * * clerk ->
 // * * device ->
 // * * micr  ->
        $ach = new Ach([
            'firstName'            => 'Example',
            'lastName'             => 'Customer',
            'routingNumber'        => '4111',
            'accountNumber'        => '2020',
            'checkNumber'          => '123',
            'billingAddress1'      => '1 Scrubby Creek Road',
            'billingCountry'       => 'AU',
            'billingCity'          => 'Scrubby Creek',
            'billingPostcode'      => '4999',
            'billingState'         => 'QLD',
        ]);

        $response = $this->gateway->purchase([
            'description'              => 'Your order for widgets',
            'amount'                   => '10.00',
            'transactionId'            => 12345,
            'clientIp'                 => "1.2.3.4",
            'ach'                     => $ach,
            // 'paymentMethod' => "ach"
        ])->send();

        $this->assertTrue($response->isSuccessful());

        $this->assertNotNull($response->getAuthorizationNumber());
        $this->assertNull($response->getCardReference());

        $this->assertNotNull($response->getTransactionTag());
        $this->assertNotNull($response->getTransactionReference());
        $this->assertNotNull($response->getSequenceNo());

        $this->assertEquals($response->getCode(),"00");
        $this->assertEquals($response->getMessage(),"Approved");
        $this->assertEquals($response->getBankCode(),"100");
        $this->assertEquals($response->getExactMessage(),"Transaction Normal");
        $this->assertEquals($response->getBankMessage(),"Approved");
    }


    /**
     * Everything was successful
     */
    public function test_ach_purchase()
    {
        $card = new CreditCard([
            'firstName'            => 'Example',
            'lastName'             => 'Customer',
            'number'               => '4111111111111111',
            'expiryMonth'          => '12',
            'expiryYear'           => '2026',
            'cvv'                  => '123',
        ]);

        $response = $this->gateway->purchase([
            'description'              => 'Your order for widgets',
            'amount'                   => '10.00',
            'transactionId'            => 12345,
            'clientIp'                 => "1.2.3.4",
            'card'                     => $card,
        ])->send();

        $this->assertTrue($response->isSuccessful());

        $this->assertNotNull($response->getAuthorizationNumber());
        $this->assertNull($response->getCardReference());

        $this->assertNotNull($response->getTransactionTag());
        $this->assertNotNull($response->getTransactionReference());
        $this->assertNotNull($response->getSequenceNo());

        $this->assertEquals($response->getCode(),"00");
        $this->assertEquals($response->getMessage(),"Approved");
        $this->assertEquals($response->getBankCode(),"100");
        $this->assertEquals($response->getExactMessage(),"Transaction Normal");
        $this->assertEquals($response->getBankMessage(),"Approved");
    }

    /**
     * An exception was thrown before the request was made because of invalid input
     */
    public function test_ach_purchase_exception()
    {
        $this->expectException(InvalidRequestException::class);
        $this->expectExceptionMessage("The amount parameter is required");
        $card = new CreditCard([
            'firstName'            => 'Example',
            'lastName'             => 'Customer',
            'number'               => '4111111111111111',
            'expiryMonth'          => '12',
            'expiryYear'           => '2019',
            'cvv'                  => '123',
        ]);

        $response = $this->gateway->purchase([
            'description'              => 'Your order for widgets',
            // 'amount'                   => '10.00',
            'transactionId'            => 12345,
            'clientIp'                 => "1.2.3.4",
            'card'                     => $card,
        ])->send();
    }

    /**
     * An exception was thrown because the response came back in a bad format
     */
    public function test_ach_purchase_error()
    {

        $this->expectException(InvalidResponseException::class);
        $this->expectExceptionMessage("Bad Request (27) - Invalid Card Holder");
        $card = new CreditCard([
            'firstName'            => 'test',
            'lastName'             => '',
            'number'               => '4111111111111111',
            'expiryMonth'          => '12',
            'expiryYear'           => '2026',
            'cvv'                  => '123',
        ]);

        $response = $this->gateway->purchase([
            'description'              => 'Your order for widgets',
            'amount'                   => '5000.27',
            'transactionId'            => 12345,
            'clientIp'                 => "1.2.3.4",
            'card'                     => $card,
        ])->send();
    }

    /**
     * No exception thrown but the payment was unsuccessful
     */
    public function test_ach_purchase_failure()
    {
        $card = new CreditCard([
            'firstName'            => 'Example',
            'lastName'             => 'Customer',
            'number'               => '4111111111111111',
            'expiryMonth'          => '12',
            'expiryYear'           => '2026',
            'cvv'                  => '123',
        ]);

        $response = $this->gateway->purchase([
            'description'              => 'Your order for widgets',
            'amount'                   => '5605.00',
            'transactionId'            => 12345,
            'clientIp'                 => "1.2.3.4",
            'card'                     => $card,
        ])->send();

        $this->assertFalse($response->isSuccessful());

        $this->assertNull($response->getAuthorizationNumber());
        $this->assertNull($response->getCardReference());

        $this->assertNotNull($response->getTransactionTag());
        $this->assertNotNull($response->getTransactionReference());
        $this->assertNotNull($response->getSequenceNo());

        $this->assertEquals($response->getCode(),"00");
        $this->assertEquals($response->getMessage(),"Invalid Expiration Date");
        $this->assertEquals($response->getBankCode(),"605");
        $this->assertEquals($response->getExactMessage(),"Transaction Normal");
        $this->assertEquals($response->getBankMessage(),"Invalid Expiration Date");
    }
}
