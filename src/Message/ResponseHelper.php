<?php
/**
 * First Data Payeezy Response
 */
namespace Omnipay\FirstData\Message;

/**
 * First Data Payeezy Response Extras
 *
 * ### Quirks
 *
 * This gateway requires both a transaction reference (aka an authorization number)
 * and a transaction tag to implement either voids or refunds.  These are referred
 * to in the documentation as "tagged refund" and "tagged voids".
 *
 * The transaction reference returned by this class' getTransactionReference is a
 * concatenated value of the authorization number and the transaction tag.
 */
trait ResponseHelper{


    /**
     * Get the email address
     *
     * @return string
     */
    public function getEmail()
    {
        return $this->getDataItem('client_email');
    }

    /**
     * Get the credit card number
     *
     * @return string
     */
    public function getCardNumber()
    {
        return $this->getDataItem('cc_number');
    }

    /**
     * Get the credit card type
     *
     * @return string
     */
    public function getCardType()
    {
        return $this->getDataItem('credit_card_type');
    }

    /**
     * Get the amount payed
     *
     * @return float
     */
    public function getAmount()
    {
        return $this->getDataItem('amount');
    }

    /**
     * Get the address array
     *
     *
     * @return array
     */
    public function getAddress()
    {
        return $this->getDataItem('address');
    }

    /**
     * Get specified address part
     *
     * @return mixed
     */
    public function getAddressPart($part)
    {
        $address = $this->getAddress();
        if($address != null && isset($address[$part])){
            return $address[$part];
        }
        return null;
    }


    /**
     * Get address line 1
     *
     * @return string
     */
    public function getAddress1()
    {
        return $this->getAddressPart("address1");
    }

    /**
     * Get address line 2
     *
     * @return string
     */
    public function getAddress2()
    {
        return $this->getAddressPart("address2");
    }

    /**
     * Get address city
     *
     * @return string
     */
    public function getCity()
    {
        return $this->getAddressPart("city");
    }

    /**
     * Get address state
     *
     * @return string
     */
    public function getState()
    {
        return $this->getAddressPart("state");
    }

    /**
     * Get address zipcode
     *
     * @return string
     */
    public function getPostCode()
    {
        return $this->getAddressPart("zip");
    }

    /**
     * Get address country code
     *
     * @return string
     */
    public function getCountry()
    {
        return $this->getAddressPart("country_code");
    }

    /**
     * Get phone type
     * Only the following values are accepted:
     * H = Home
     * W = Work
     * D = Day
     * N = Night
     * PhoneType is required when the PhoneNumber field is populated in a transaction request. Otherwise, it is optional.
     * @return string
     */
    public function getPhoneType(){
        return $this->getAddressPart("phone_type");
    }


    /**
     * Get phone number
     * Non digits will be removed before processing. When phone_number is used, phone_type must be provided.
     *
     * @return string
     */
    public function getPhone()
    {
        return $this->getAddressPart('phone_number');
    }
}
