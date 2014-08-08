<?php

namespace Omnipay\FirstData2;

use Omnipay\Common\AbstractGateway;

/**
 * Global Gateway
 *
 * This gateway is useful for testing. It simply authorizes any payment made using a valid
 * credit card number and expiry.
 *
 * Any card number which passes the Luhn algorithm and ends in an even number is authorized,
 * for example: 4242424242424242
 *
 * Any card number which passes the Luhn algorithm and ends in an odd number is declined,
 * for example: 4111111111111111
 */
class GlobalGateway extends AbstractGateway
{
    public function getName()
    {
        return 'First Data Global';
    }

    public function authorize(array $parameters = array())
    {
        return $this->createRequest('\Omnipay\FirstData2\Message\FirstData', $parameters);
    }

    public function purchase(array $parameters = array())
    {
        return $this->createRequest('\Omnipay\FirstData2\Message\PurchaseRequest', $parameters);
    }

    public function getDefaultParameters()
    {
        return array(
            'gatewayid' => '',
            'password' => '',
            'testMode' => false,
        );
    }

    public function getGatewayid()
    {
        return $this->getParameter('gatewayid');
    }

    public function setGatewayid($value)
    {
        return $this->setParameter('gatewayid', $value);
    }

    public function getPassword()
    {
        return $this->getParameter('password');
    }

    public function setPassword($value)
    {
        return $this->setParameter('password', $value);
    }
}
