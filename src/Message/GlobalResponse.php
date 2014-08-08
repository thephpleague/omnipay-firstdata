<?php

namespace Omnipay\FirstData2\Message;

use Omnipay\Common\Message\AbstractResponse;
use Omnipay\Common\Message\RequestInterface;

/**
 * PayPal Response
 */
class Response extends AbstractResponse
{
    public function __construct(RequestInterface $request, $data)
    {
        $this->request = $request;
        parse_str($data, $this->data);
    }

    public function isSuccessful()
    {
        return ($this->data['exact_resp_code'] == '00')?true:false;
    }

    public function getTransactionReference()
    {
        return $this->data['reference_no'];
    }

    public function getMessage()
    {
        return $this->data['exact_message'];
    }
    public function getCode()
    {
        return $this->data['exact_resp_code'];
    }
}
