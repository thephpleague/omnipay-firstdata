<?php

namespace Omnipay\FirstData\Message;

use Omnipay\Tests\TestCase;
use Omnipay\FirstData\Message\PayeezyPurchaseRequest;

class PayeezyPurchaseResponseTest extends TestCase
{
    public function testPurchaseSuccess()
    {
        $response = new PayeezyResponse($this->getMockRequest(), json_encode(array(
            'amount' => 1000,
            'exact_resp_code' => 00,
            'bank_resp_code' => 100,
            'exact_message' => 'Transaction Normal',
            'reference_no' => 'abc123',
            'authorization_num' => 'auth1234',
            'bank_message' => "Approved",
            'transaction_approved' => 1,
        )));

        $this->assertTrue($response->isSuccessful());
        $this->assertEquals('auth1234::', $response->getTransactionReference());
        $this->assertSame('Approved', $response->getMessage());
        $this->assertSame('Approved', $response->getBankMessage());
        $this->assertSame('Transaction Normal', $response->getExactMessage());

        $this->assertEquals('00', $response->getCode());
        $this->assertEquals('100', $response->getBankCode());

    }

    public function testPurchaseError()
    {
        $response = new PayeezyResponse($this->getMockRequest(), json_encode(array(
            'amount' => 1000,
            'exact_resp_code' => 22,
            'bank_resp_code' => 605,
            'exact_message' => 'Transaction Normal',
            'reference_no' => 'abc123',
            'authorization_num' => 'auth1234',
            'bank_message' => 'Invalid Expiration Date',
            'transaction_approved' => 0,
        )));

        $this->assertFalse($response->isSuccessful());
        $this->assertEquals('auth1234::', $response->getTransactionReference());
        $this->assertSame('Invalid Expiration Date', $response->getMessage());
        $this->assertSame('Invalid Expiration Date', $response->getBankMessage());
        $this->assertSame('Transaction Normal', $response->getExactMessage());
        $this->assertEquals('22', $response->getCode());
        $this->assertEquals('605', $response->getBankCode());

    }

    public function testBankError()
    {
        $response = new PayeezyResponse($this->getMockRequest(), json_encode(array(
            'amount' => 1000,
            'exact_resp_code' => 00,
            'bank_resp_code' => 605,
            'reference_no' => 'abc123',
            'authorization_num' => '',
            'transaction_approved' => 0,
        )));

        $this->assertFalse($response->isSuccessful());
        $this->assertEquals('::', $response->getTransactionReference());
        $this->assertEquals('00', $response->getCode());
        $this->assertEquals('605', $response->getBankCode());

    }
}
