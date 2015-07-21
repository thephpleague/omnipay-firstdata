<?php

namespace Omnipay\FirstData\Message;

use Omnipay\Common\Message\AbstractRequest;

/**
 * FirstDataConnect Authorize Request
 */
class PurchaseRequest extends AbstractRequest
{
    protected $liveEndpoint = 'https://www.ipg-online.com/connect/gateway/processing';
    protected $testEndpoint = 'https://test.ipg-online.com/connect/gateway/processing';

    protected function getDateTime()
    {
        return date("Y:m:d-H:i:s");
    }

    public function getStoreId()
    {
        return $this->getParameter('storeId');
    }

    public function setStoreId($value)
    {
        return $this->setParameter('storeId', $value);
    }

    public function setSharedSecret($value)
    {
        return $this->setParameter('sharedSecret', $value);
    }

    public function getSharedSecret()
    {
        return $this->getParameter('sharedSecret');
    }

    public function setHostedDataId($value)
    {
        return $this->setParameter('hostedDataId', $value);
    }

    public function getHostedDataId()
    {
        return $this->getParameter('hostedDataId');
    }

    public function setCustomerId($value)
    {
        return $this->setParameter('customerId', $value);
    }

    public function getCustomerId()
    {
        return $this->getParameter('customerId');
    }

    public function getData()
    {
        $this->validate('amount');

        $data = array();
        $data['storename'] = $this->getStoreId();
        $data['txntype'] = 'sale';
        $data['timezone'] = 'GMT';
        $data['chargetotal'] = $this->getAmount();
        $data['txndatetime'] = $this->getDateTime();
        $data['hash'] = $this->createHash($data['txndatetime'], $data['chargetotal']);
        $data['currency'] = $this->getCurrencyNumeric();
        $data['mode'] = 'payonly';
        $data['full_bypass'] = 'true';
        $data['oid'] = $this->getParameter('transactionId');

        // Card is only required if no hosteddataid (saved 'data vault' card)
        if (is_null($this->getHostedDataId())) {
            $this->validate('card');
        }

        // If a card is passed, validate it
        if (!is_null($this->getCard())) {

            $this->getCard()->validate();

            $data['cardnumber'] = $this->getCard()->getNumber();
            $data['cvm'] = $this->getCard()->getCvv();
            $data['expmonth'] = $this->getCard()->getExpiryDate('m');
            $data['expyear'] = $this->getCard()->getExpiryDate('y');

            $data['bname'] = $this->getCard()->getBillingName();
            $data['baddr1'] = $this->getCard()->getBillingAddress1();
            $data['baddr2'] = $this->getCard()->getBillingAddress2();
            $data['bcity'] = $this->getCard()->getBillingCity();
            $data['bstate'] = $this->getCard()->getBillingState();
            $data['bcountry'] = $this->getCard()->getBillingCountry();
            $data['bzip'] = $this->getCard()->getBillingPostcode();

            $data['sname'] = $this->getCard()->getShippingName();
            $data['saddr1'] = $this->getCard()->getShippingAddress1();
            $data['saddr2'] = $this->getCard()->getShippingAddress2();
            $data['scity'] = $this->getCard()->getShippingCity();
            $data['sstate'] = $this->getCard()->getShippingState();
            $data['scountry'] = $this->getCard()->getShippingCountry();
            $data['szip'] = $this->getCard()->getShippingPostcode();

            $data['phone'] = $this->getCard()->getPhone();
            $data['email'] = $this->getCard()->getEmail();
        }

        $data['responseSuccessURL'] = $this->getParameter('returnUrl');
        $data['responseFailURL'] = $this->getParameter('returnUrl');

        $data['customerid'] = $this->getCustomerId();

        $data['hosteddataid'] = $this->getHostedDataId();

        return $data;
    }

    public function createHash($dateTime, $amount)
    {
        $storeId = $this->getStoreId();
        $sharedSecret = $this->getSharedSecret();
        $currency = $this->getCurrencyNumeric();
        $stringToHash = $storeId . $dateTime . $amount . $currency . $sharedSecret;
        $ascii = bin2hex($stringToHash);

        return sha1($ascii);
    }

    public function sendData($data)
    {
        return $this->response = new PurchaseResponse($this, $data);
    }

    public function getEndpoint()
    {
        return $this->getTestMode() ? $this->testEndpoint : $this->liveEndpoint;
    }
}
