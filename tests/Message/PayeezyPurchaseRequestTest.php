<?php

namespace Omnipay\FirstData\Message;

use Omnipay\Tests\TestCase;
use Omnipay\FirstData\Message\PayeezyPurchaseRequest;

class PayeezyPurchaseRequestTest extends TestCase
{
    public function testPurchaseSuccessV13()
    {
        $request = new PayeezyPurchaseRequest($this->getHttpClient(), $this->getHttpRequest());
        $request->setApiVersion(13)
        ->initialize([
            'amount' => '12.00',
            'card' => $this->getValidCard(),
        ]);

        $this->assertEquals(13,$request->getApiVersion());
        $data = $request->getData();
        $this->assertEquals('00', $data['transaction_type']);
        $this->assertEquals('4111111111111111', $data['cc_number']);
        $this->assertEquals('Visa', $data['credit_card_type']);
        $this->assertEquals('12.00', $data['amount']);
        $this->assertNotNull($data['cc_verification_str2']);

        $this->assertEquals('123 Billing St|12345|Billstown|CA|US', $data['cc_verification_str1']);
    }

    public function testPurchaseSuccess()
    {
        $request = new PayeezyPurchaseRequest($this->getHttpClient(), $this->getHttpRequest());
        $request->initialize([
            'amount' => '12.00',
            'card' => $this->getValidCard(),
        ]);

        $this->assertEquals(14,$request->getApiVersion());
        $data = $request->getData();
        $this->assertEquals('00', $data['transaction_type']);
        $this->assertEquals('4111111111111111', $data['cc_number']);
        $this->assertEquals('Visa', $data['credit_card_type']);
        $this->assertEquals('12.00', $data['amount']);
        $this->assertNotNull($data['cvd_code']);

        $this->assertEquals([
            'address1' => '123 Billing St',
            'zip' => '12345',
            'address2' => 'Billsville',
            'city' => 'Billstown',
            'phone_type' => 'N',
            'state' => 'CA',
            'country_code' => 'US',
            'phone_number' => '(555) 123-4567'
        ], $data['address']);
    }



    public function testPurchaseSuccessMaestroType()
    {
        $options = [
            'amount' => '12.00',
            'card' => $this->getValidCard(),
        ];

        $options['card']['number'] = '6304000000000000';

        $request = new PayeezyPurchaseRequest($this->getHttpClient(), $this->getHttpRequest());
        $request->setApiVersion(13)
        ->initialize($options);

        $data = $request->getData();
        $this->assertEquals('00', $data['transaction_type']);
        $this->assertEquals('maestro', $data['credit_card_type']);
    }
}
