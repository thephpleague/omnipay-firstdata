<?php

namespace Omnipay\FirstData\Message;

class GlobalRefundRequest extends GlobalAbstractRequest
{
    protected $action = self::TRAN_TAGGEDREFUND;

    public function getData()
    {
        $this->setTransactionType($this->action);
        $data = $this->getBaseData('DoDirectPayment');

        $this->validate('amount', 'transactionReference', 'authorizationCode');

        $data['amount'] = $this->getAmount();
        $data['currency_code'] = $this->getCurrency();
        $data['transaction_tag'] = $this->getTransactionReference();
        $data['authorization_num'] = $this->getAuthorizationCode();

        $data['client_ip'] = $this->getClientIp();
        return $data;
    }


    /**
     * Get the transaction ID.
     *
     * @return string
     */
    public function getAuthorizationCode()
    {
        return $this->getParameter('authorizationCode');
    }

    /**
     * Sets the transaction ID.
     *
     * @param string $value
     * @return AbstractRequest Provides a fluent interface
     */
    public function setAuthorizationCode($value)
    {
        return $this->setParameter('authorizationCode', $value);
    }
}
