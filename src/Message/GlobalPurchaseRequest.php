<?php

namespace Omnipay\FirstData\Message;

class GlobalPurchaseRequest extends GlobalAbstractRequest
{
    public function getData()
    {
        $this->setTransactionType(GlobalAbstractRequest::TRAN_PURCHASE);
        $data = $this->getBaseData('DoDirectPayment');

        $this->validate('amount', 'card');

        $data['amount'] = $this->getAmount();
        $data['currency_code'] = $this->getCurrency();
        $data['reference_no'] = $this->getTransactionId();

        // add credit card details
        $data['credit_card_type'] = self::getCardType($this->getCard()->getBrand());
        $data['cc_number'] = $this->getCard()->getNumber();
        $data['cardholder_name'] = $this->getCard()->getName();
        $data['cc_expiry'] = $this->getCard()->getExpiryDate('my');
        $data['cc_verification_str2'] = $this->getCard()->getCvv();
        $data['cc_verification_str1'] = $this->getAVSHash();
        $data['cvd_presence_ind'] = 1;
        $data['cvd_code'] = $this->getCard()->getCvv();

        $data['client_ip'] = $this->getClientIp();
        $data['client_email'] = $this->getCard()->getEmail();
        $data['language'] = strtoupper($this->getCard()->getCountry());
        return $data;
    }

    public function getAVSHash()
    {
        $parts = array();
        $parts[] = $this->getCard()->getAddress1();
        $parts[] = $this->getCard()->getPostcode();
        $parts[] = $this->getCard()->getCity();
        $parts[] = $this->getCard()->getState();
        $parts[] = $this->getCard()->getCountry();
        return implode('|', $parts);
    }
}
