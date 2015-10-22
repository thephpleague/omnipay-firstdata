<?php

namespace Omnipay\FirstData\Message;

use Omnipay\Tests\TestCase;
use Omnipay\FirstData\Message\GlobalRefundRequest;

class GlobalRefundRequestTest extends TestCase
{
    public function testRefundRequest()
    {
        $request = new GlobalRefundRequest($this->getHttpClient(), $this->getHttpRequest());
        $request->initialize(
            array(
                'amount' => 13.00,
                'transactionReference' => '28513493',
                'authorizationCode' => 'ET181147'
            )
        );

        $data = $request->getData();
        $this->assertEquals('34', $data['transaction_type']);
        $this->assertEquals('28513493', $data['transaction_tag']);
        $this->assertEquals('ET181147', $data['authorization_num']);
    }

}
