<?php

namespace Omnipay\FirstData;

use Omnipay\Common\CreditCard;
use Omnipay\Common\Exception\InvalidCreditCardException;
use Omnipay\Common\Exception\InvalidRequestException;
use Omnipay\FirstData\Exception\InvalidAchException;
use Omnipay\Tests\GatewayTestCase;

class PayeezyAchGatewayTest extends GatewayTestCase
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
            'ach' => $this->getValidAch(),
            'transactionId' => 'order2',
            'currency' => 'USD',
            'testMode' => true,
        );
    }

    public function testPurchaseAchSuccess()
    {
        $this->setMockHttpResponse('PurchaseAchSuccess.txt');

        $response = $this->gateway->purchase($this->options)->send();

        $this->assertTrue($response->isSuccessful());
        $this->assertFalse($response->isRedirect());
        $this->assertEquals('ET173315::4549770020', $response->getTransactionReference());
        $this->assertEquals('000027', $response->getSequenceNo());
        $this->assertEmpty($response->getCardReference());
    }

    public function testAuthorizeSuccess()
    {
        $this->setMockHttpResponse('PurchaseAchSuccess.txt');

        $response = $this->gateway->authorize($this->options)->send();

        $this->assertTrue($response->isSuccessful());
        $this->assertFalse($response->isRedirect());
        $this->assertEquals('ET173315::4549770020', $response->getTransactionReference());
    }

    public function testPurchaseFailureMissingAmount(){

        $this->expectException(InvalidRequestException::class);

        unset($this->options['amount']);
        $response = $this->gateway->purchase($this->options)->send();
    }

    public function testPurchaseFailureInvalidAch(){
        $this->expectException(InvalidAchException::class);
        $this->options['ach'] = new Ach([
            'firstName' => 'Example',
            'lastName' => 'User',
        ]);
        $response = $this->gateway->purchase($this->options)->send();
    }

    public function testPurchaseAchWithLicense(){
        $this->options['ach'] = $this->getValidAch();
        $this->options['ach']['license'] = "123456";
        $this->options['ach']['license_state'] = "PA";
        $request = $this->gateway->purchase($this->options)->getData();

        $this->assertEquals($request['customer_id_type'],0);
        $this->assertEquals($request['customer_id_number'],"123456");
    }



    public function testPurchaseAchWithSsn(){
        $this->options['ach'] = $this->getValidAch();
        $this->options['ach']['ssn'] = "123456789";
        $request = $this->gateway->purchase($this->options)->getData();

        $this->assertEquals($request['customer_id_type'],1);
        $this->assertEquals($request['customer_id_number'],"123456789");
    }

    public function testPurchaseAchWithTaxId(){
        $this->options['ach'] = $this->getValidAch();
        $this->options['ach']['taxId'] = "123456";
        $request = $this->gateway->purchase($this->options)->getData();

        $this->assertEquals($request['customer_id_type'],2);
        $this->assertEquals($request['customer_id_number'],"123456");
    }

    public function testPurchaseAchWithMilitaryId(){
        $this->options['ach'] = $this->getValidAch();
        $this->options['ach']['militaryId'] = "123456";
        $request = $this->gateway->purchase($this->options)->getData();

        $this->assertEquals($request['customer_id_type'],3);
        $this->assertEquals($request['customer_id_number'],"123456");
    }

    public function testPurchaseAchWithMultipleAuths(){
        $ach = new Ach($this->getValidAch());
        $ach->setMilitaryId("123456");
        $ach->setLicense('ABCDEF');
        $this->options['ach'] = $ach;
        $request = $this->gateway->purchase($this->options)
        ->getData();

        $this->assertEquals($request['customer_id_type'],0);
        $this->assertEquals($request['customer_id_number'],"ABCDEF");
    }

    public function testPurchaseAchWithChangedAuths(){
        $ach = new Ach($this->getValidAch());
        $ach->setLicense('ABCDEF');
        $ach->setMilitaryId("123456");
        $ach->setLicense(null);

        $this->options['ach'] = $ach;
        $request = $this->gateway->purchase($this->options)
        ->getData();

        $this->assertEquals($request['customer_id_type'],3);
        $this->assertEquals($request['customer_id_number'],"123456");
    }

    public function testPurchaseAchWithRemovedAuth(){
        $ach = new Ach($this->getValidAch());
        $ach->setLicense('ABCDEF');
        $ach->setLicense(NULL);

        $this->options['ach'] = $ach;
        $request = $this->gateway->purchase($this->options)
        ->getData();

        $this->assertFalse(isset($request['customer_id_type']));
        $this->assertFalse(isset($request['customer_id_number']));
    }


    /**
     * Helper method used by gateway test classes to generate a valid test credit card
     */
    private function getValidAch()
    {
        return array(
            'firstName' => 'Example',
            'lastName' => 'User',
            'routingNumber' => '021000021',
            'accountNumber' => '123456789',
            'checkNumber' => rand(100,999),
            'billingAddress1' => '123 Billing St',
            'billingAddress2' => 'Billsville',
            'billingCity' => 'Billstown',
            'billingPostcode' => '12345',
            'billingState' => 'CA',
            'billingCountry' => 'US',
            'billingPhone' => '(555) 123-4567',
            'shippingAddress1' => '123 Shipping St',
            'shippingAddress2' => 'Shipsville',
            'shippingCity' => 'Shipstown',
            'shippingPostcode' => '54321',
            'shippingState' => 'NY',
            'shippingCountry' => 'US',
            'shippingPhone' => '(555) 987-6543',
        );
    }
}
