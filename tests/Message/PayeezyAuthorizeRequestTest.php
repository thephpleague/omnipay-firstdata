<?php

namespace Omnipay\FirstData\Message;

use Omnipay\Tests\TestCase;

class PayeezyAuthorizeRequestTest extends TestCase
{
    public function testAuthorizeSuccessV13()
    {
        $request = new PayeezyAuthorizeRequest($this->getHttpClient(), $this->getHttpRequest());
        $request->setApiVersion(13)
        ->initialize(
            array(
                'amount' => '12.00',
                'card' => $this->getValidCard(),
            )
        );
        $this->assertEquals(13,$request->getApiVersion());

        $data = $request->getData();
        $this->assertEquals('01', $data['transaction_type']);
        $this->assertEquals('4111111111111111', $data['cc_number']);
        $this->assertEquals('Visa', $data['credit_card_type']);
        $this->assertEquals('12.00', $data['amount']);
        $this->assertNotNull($data['cc_verification_str2']);
        $this->assertEquals('123 Billing St|12345|Billstown|CA|US', $data['cc_verification_str1']);
    }

    public function testAuthorizeSuccess()
    {
        $request = new PayeezyAuthorizeRequest($this->getHttpClient(), $this->getHttpRequest());
        $request->initialize(
            array(
                'amount' => '12.00',
                'card' => $this->getValidCard(),
            )
        );
        $this->assertEquals(14,$request->getApiVersion());

        $data = $request->getData();
        $this->assertEquals('01', $data['transaction_type']);
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
}
