<?php

namespace Omnipay\FirstData\Feature;

use Omnipay\Common\CreditCard;
use Omnipay\Common\Exception\InvalidRequestException;
use Omnipay\Common\Exception\InvalidResponseException;
use Omnipay\FirstData\Ach;
use Omnipay\FirstData\Exception\InvalidAchException;
use Omnipay\Omnipay;
use Omnipay\Tests\GatewayTestCase;
use PHPUnit\Framework\TestCase;

class DemoPayeezyAchGatewayTest extends TestCase
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

    /**
     * Everything was successful
     */
    public function test_ach_purchase_was_successful()
    {
        $ach = new Ach([
            'firstName'            => 'Example',
            'lastName'             => 'Customer',
            'routingNumber'        => '021000021',
            'accountNumber'        => '2020',
            'checkNumber'          => '123',
            // 'checkType'            => 'P',
        ]);

        $response = $this->gateway->purchase([
            'description'              => 'Your order for widgets',
            'amount'                   => '10.00',
            'transactionId'            => 12345,
            'clientIp'                 => "1.2.3.4",
            'ach'                      => $ach,
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

        $this->assertEquals($response->getCardType(),"Telecheck");
        $this->assertNull($response->getCardNumber());
        $this->assertEquals($response->getCheckNumber(),2020);


        $this->assertNull($response->getEmail());


    }

    public function test_ach_purchase_with_additional_fields()
    {

 // * * checkType
 // * * accountNumber
 // * * routingNumber
 // * * checkNumber
 // * * customerIDType ->
 // * * license ->
 // * * licenseState ->
 // * * ssn ->
 // * * taxId ->
 // * * militaryId ->
 // *
 // * * customer ->
 // * * ecommerceFlag ->
 // * * releaseType ->
 // * * vip ->
 // * * clerk ->
 // * * device ->
 // * * micr  ->
        $ach = new Ach([
            'firstName'            => 'Example',
            'lastName'             => 'Customer',
            'routingNumber'        => '021000021',
            'accountNumber'        => '2020',
            'checkNumber'          => '123',
            'address1'             => '1 Scrubby Creek Road',
            'country'              => 'AU',
            'city'                 => 'Scrubby Creek',
            'postcode'             => '4999',
            'state'                => 'PA',
            'phone'                => '5551234567',
            'email'                => 'example@email.com',
            'checkType'            => 'C',
            'release_type'         => 'D',
            'vip'                  => false,
            'clerk'                => 'AAAA',
            'device'               => 'BBBB',
            'micr'                 => 'CCCC',
            'ecommerce_flag'       => 7,
        ]);

        $response = $this->gateway->purchase([
            'description'              => 'Your order for widgets',
            'amount'                   => '10.00',
            'transactionId'            => 12345,
            'clientIp'                 => "1.2.3.4",
            'ach'                      => $ach,
            // 'customerIDType'           => 0
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

        $this->assertEquals($response->getCardType(),"Telecheck");
        $this->assertNull($response->getCardNumber());
        $this->assertEquals($response->getCheckNumber(),123);

        $this->assertNotNull($response->getAddress());
        $this->assertEquals($response->getEmail(),"example@email.com");
        $this->assertEquals($response->getAddress1(), '1 Scrubby Creek Road');
        $this->assertNull($response->getAddress2());
        $this->assertEquals($response->getCountry(), 'AU');
        $this->assertEquals($response->getCity(), 'Scrubby Creek');
        $this->assertEquals($response->getPostCode(), '4999');
        $this->assertEquals($response->getState(), 'PA');
        $this->assertEquals($response->getPhone(), '5551234567');

        $this->assertEquals($response->getCheckType(),"C");

        $this->assertEquals($response->getCheckType(),"C");
        $this->assertEquals($response->getReleaseType(),'D');
        $this->assertEquals($response->getVip(),false);
        $this->assertEquals($response->getClerk(),'AAAA');
        $this->assertEquals($response->getEcommerceFlag(),7);
        $this->assertNotNull($response->getCtr());
    }

    public function test_ach_purchase_with_address()
    {
        $ach = new Ach([
            'firstName'            => 'Example',
            'lastName'             => 'Customer',
            'routingNumber'        => '021000021',
            'accountNumber'        => '2020',
            'checkNumber'          => '123',
            'checkType'            => 'P',
            'address1'             => '1 Scrubby Creek Road',
            'country'              => 'AU',
            'city'                 => 'Scrubby Creek',
            'postcode'             => '4999',
            'state'                => 'PA',
            'phone'                => '5551234567',
            'email'                => 'example@email.com'
        ]);

        $response = $this->gateway->purchase([
            'description'              => 'Your order for widgets',
            'amount'                   => '10.00',
            'transactionId'            => 12345,
            'clientIp'                 => "1.2.3.4",
            'ach'                      => $ach,
            // 'customerIDType'           => 0
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

        $this->assertEquals($response->getCardType(),"Telecheck");
        $this->assertNull($response->getCardNumber());
        $this->assertEquals($response->getCheckNumber(),2020);

        $this->assertNotNull($response->getAddress());
        $this->assertEquals($response->getEmail(),"example@email.com");
        $this->assertEquals($response->getAddress1(), '1 Scrubby Creek Road');
        $this->assertNull($response->getAddress2());
        $this->assertEquals($response->getCountry(), 'AU');
        $this->assertEquals($response->getCity(), 'Scrubby Creek');
        $this->assertEquals($response->getPostCode(), '4999');
        $this->assertEquals($response->getState(), 'PA');
        $this->assertEquals($response->getPhone(), '5551234567');
    }

    public function test_ach_purchase_with_auth()
    {
        $ach = new Ach([
            'firstName'            => 'Example',
            'lastName'             => 'Customer',
            'routingNumber'        => '021000021',
            'accountNumber'        => '2020',
            'checkNumber'          => '123',
            'checkType'            => 'P',
            'address1'             => '1 Scrubby Creek Road',
            'country'              => 'AU',
            'city'                 => 'Scrubby Creek',
            'postcode'             => '4999',
            'state'                => 'PA',
            'phone'                => '5551234567',
            'email'                => 'example@email.com',
            'license'              => '123456',
            'license_state'        => 'PA',
        ]);

        $response = $this->gateway->purchase([
            'description'              => 'Your order for widgets',
            'amount'                   => '10.00',
            'transactionId'            => 12345,
            'clientIp'                 => "1.2.3.4",
            'ach'                      => $ach,
        ])->send();

        $this->assertTrue($response->isSuccessful());
    }

    /**
     * An exception was thrown before the request was made because of invalid input
     */
    public function test_ach_purchase_exception()
    {
        $this->expectException(InvalidRequestException::class);
        $this->expectExceptionMessage("The amount parameter is required");
        $ach = new Ach([
            'firstName'            => 'Example',
            'lastName'             => 'Customer',
            'routingNumber'        => '021000021',
            'accountNumber'        => '2020',
            'checkNumber'          => '123',
        ]);

        $response = $this->gateway->purchase([
            'description'              => 'Your order for widgets',
            // 'amount'                   => '10.00',
            'transactionId'            => 12345,
            'clientIp'                 => "1.2.3.4",
            'ach'                     => $ach,
        ])->send();

    }

    /**
     * An exception was thrown before the request was made because of invalid input
     */
    public function test_ach_purchase_ach_exception()
    {
        $this->expectException(InvalidAchException::class);
        $this->expectExceptionMessage("The routing number is required");
        $ach = new Ach([
            'firstName'            => 'Example',
            'lastName'             => 'Customer',
            // 'routingNumber'        => '021000021',
            'accountNumber'        => '2020',
            'checkNumber'          => '123',
        ]);

        $response = $this->gateway->purchase([
            'description'              => 'Your order for widgets',
            'amount'                   => '10.00',
            'transactionId'            => 12345,
            'clientIp'                 => "1.2.3.4",
            'check'                     => $ach,
        ])->send();

    }
    /**
     * An exception was thrown because the response came back in a bad format
     */
    public function test_ach_purchase_error()
    {

        $this->expectException(InvalidResponseException::class);
        $this->expectExceptionMessage("Bad Request (69) - Invalid Transaction Tag");
        $ach = new Ach([
            'firstName'            => 'Example',
            'lastName'             => 'Customer',
            'routingNumber'        => '021000021',
            'accountNumber'        => '2020',
            'checkNumber'          => '123',
        ]);

        $response = $this->gateway->purchase([
            'description'              => 'Your order for widgets',
            'amount'                   => '5000.69',
            'transactionId'            => 12345,
            'clientIp'                 => "1.2.3.4",
            'ach'                     => $ach,
        ])->send();
    }

    /**
     * No exception thrown but the payment was unsuccessful
     */
    public function test_ach_purchase_failure()
    {
        $ach = new Ach([
            'firstName'            => 'Example',
            'lastName'             => 'Customer',
            'routingNumber'        => '021000021',
            'accountNumber'        => '2020',
            'checkNumber'          => '123',
        ]);

        $response = $this->gateway->purchase([
            'description'              => 'Your order for widgets',
            'amount'                   => '5299.00',
            'transactionId'            => 12345,
            'clientIp'                 => "1.2.3.4",
            'ach'                     => $ach,
        ])->send();

        $this->assertFalse($response->isSuccessful());

        $this->assertNull($response->getAuthorizationNumber());
        $this->assertNull($response->getCardReference());

        $this->assertNotNull($response->getTransactionTag());
        $this->assertNotNull($response->getTransactionReference());
        $this->assertNotNull($response->getSequenceNo());

        $this->assertEquals($response->getCode(),"00");
        $this->assertEquals($response->getMessage(),"Transaction not approved");
        $this->assertEquals($response->getBankCode(),"299");
        $this->assertEquals($response->getExactMessage(),"Transaction Normal");
        $this->assertEquals($response->getBankMessage(),"Transaction not approved");
    }
}
