<?php
/**
 * Credit Ach class
 */

namespace Omnipay\FirstData;

use DateTime;
use DateTimeZone;
use Omnipay\Common\Helper;
use Omnipay\Common\ParametersTrait;
use Omnipay\FirstData\Exception\InvalidAchException;
use Symfony\Component\HttpFoundation\ParameterBag;

/**
 * Ach class
 *
 * This class defines and abstracts all of the ach types used
 * throughout the Omnipay system.
 *
 * Example:
 *
 * <code>
 *   // Define ach parameters, which should look like this
 *   $parameters = [
 *       'firstName' => 'Bobby',
 *       'lastName' => 'Tables',
 *       'accountNumber' => '856667',
 *       'routingNumber' => '072403004',
 *       'checkNumber' => '1002',
 *       'email' => 'testach@gmail.com',
 *   ];
 *
 *   // Create an ach object
 *   $ach = new Ach($parameters);
 * </code>
 *
 * The full list of ach attributes that may be set via the parameter to
 * *new* is as follows:
 *
 * * checkType
 * * accountNumber
 * * routingNumber
 * * checkNumber
 * * driversLicense
 * * driversLicenseState
 * * ssn
 * * taxId
 * * militaryId
 *
 * * customer
 * * ecommerceFlag
 * * releaseType
 * * vip
 * * clerk
 * * device
 * * micr
 *
 * * title
 * * firstName
 * * lastName
 * * name
 * * company
 * * address1
 * * address2
 * * city
 * * postcode
 * * state
 * * country
 * * phone
 * * phoneExtension
 * * fax
 * * cvv
 * * billingTitle
 * * billingName
 * * billingFirstName
 * * billingLastName
 * * billingCompany
 * * billingAddress1
 * * billingAddress2
 * * billingCity
 * * billingPostcode
 * * billingState
 * * billingCountry
 * * billingPhone
 * * billingFax
 * * shippingTitle
 * * shippingName
 * * shippingFirstName
 * * shippingLastName
 * * shippingCompany
 * * shippingAddress1
 * * shippingAddress2
 * * shippingCity
 * * shippingPostcode
 * * shippingState
 * * shippingCountry
 * * shippingPhone
 * * shippingFax
 * * email
 * * birthday
 * * gender
 *
 * If any unknown parameters are passed in, they will be ignored.  No error is thrown.
 */
class Ach
{
    use ParametersTrait;

    /**
     * Create a new Ach object using the specified parameters
     *
     * @param array $parameters An array of parameters to set on the new object
     */
    public function __construct($parameters = null)
    {
        $this->initialize($parameters);
    }


    /**
     * Initialize the object with parameters.
     *
     * If any unknown parameters passed, they will be ignored.
     *
     * @param array $parameters An associative array of parameters
     * @return $this
     */
    public function initialize(array $parameters = null)
    {
        $this->parameters = new ParameterBag;

        Helper::initialize($this, $parameters);

        return $this;
    }

    /**
     * Validate this credit ach. If the ach is invalid, InvalidAchException is thrown.
     *
     * This method is called internally by gateways to avoid wasting time with an API call
     * when the credit ach is clearly invalid.
     *
     * Generally if you want to validate the credit ach yourself with custom error
     * messages, you should use your framework's validation library, not this method.
     *
     * @return void
     * @throws Exception\InvalidRequestException
     * @throws InvalidAchException
     */
    public function validate()
    {
        $requiredParameters = array(
            'firstName' => 'first name',
            'lastName' => 'last name',
            'routingNumber' => 'routing number',
            'accountNumber' => 'account number',
            // 'checkNumber' => 'check number' // number => 'cehck number'
        );

        var_dump($requiredParameters);
        die();

        foreach ($requiredParameters as $key => $val) {
            if (!$this->getParameter($key)) {
                throw new InvalidAchException("The $val is required");
            }
        }

        if (!AchHelper::validateRoutingNumber($this->getRoutingNumber())) {
            throw new InvalidAchException('Routing Number is invalid');
        }

        if (!AchHelper::validateIBAN($this->getAccountNumber())) {
            throw new InvalidAchException('Account Number is invalid');
        }
    }

    /**
     * Get the ach routing number.
     *
     * @return string
     */
    public function getRoutingNumber()
    {
        return $this->getParameter('routingNumber');
    }

    /**
     * Sets the ach routing number.
     *
     * @param string $value
     * @return $this
     */
    public function setRoutingNumber($value)
    {
        return $this->setParameter('routingNumber', $value);
    }

    /**
     * Get the ach account number.
     *
     * @return string
     */
    public function getAccountNumber()
    {
        return $this->getParameter('accountNumber');
    }

    /**
     * Sets the ach account number.
     *
     * @param string $value
     * @return $this
     */
    public function setAccountNumber($value)
    {
        return $this->setParameter('accountNumber', $value);
    }

    /**
     * Get the ach check number.
     *
     * @return string
     */
    public function getCheckNumber()
    {
        return $this->getParameter('checkNumber');
    }

    /**
     * Sets the ach check number.
     *
     * @param string $value
     * @return $this
     */
    public function setCheckNumber($value)
    {
        return $this->setParameter('checkNumber', $value);
    }

    /**
     * Get the ach check type.
     *
     * @return string
     */
    public function getCheckType()
    {
        return $this->getParameter('checkType');
    }

    /**
     * Sets the ach check type.
     *
     * @param string $value
     * @return $this
     */
    public function setCheckType($value)
    {
        return $this->setParameter('checkType', $value);
    }

    /**
     * Get the ach drivers license.
     *
     * @return string
     */
    public function getLicense()
    {
        return $this->getParameter('license');
    }

    /**
     * Sets the ach drivers license.
     *
     * @param string $value
     * @return $this
     */
    public function setLicense($value)
    {
        return $this->setParameter('license', $value);
    }

    /**
     * Get the ach drivers license state.
     *
     * @return string
     */
    public function getLicenseState()
    {
        return $this->getParameter('licenseState');
    }

    /**
     * Sets the ach drivers license state.
     *
     * @param string $value
     * @return $this
     */
    public function setLicenseState($value)
    {
        return $this->setParameter('licenseState', $value);
    }

    /**
     * Get the ach social security number.
     *
     * @return string
     */
    public function getSocialSecurityNumber()
    {
        return $this->getParameter('ssn');
    }

    /**
     * Sets the ach social security number.
     *
     * @param string $value
     * @return $this
     */
    public function setSocialSecurityNumber($value)
    {
        return $this->setParameter('ssn', $value);
    }

    /**
     * Get the ach tax ID.
     *
     * @return string
     */
    public function getTaxID()
    {
        return $this->getParameter('taxId');
    }

    /**
     * Sets the ach tax ID.
     *
     * @param string $value
     * @return $this
     */
    public function setTaxID($value)
    {
        return $this->setParameter('taxId', $value);
    }

    /**
     * Get the ach military ID.
     *
     * @return string
     */
    public function getMilitaryID()
    {
        return $this->getParameter('militaryId');
    }

    /**
     * Sets the ach military ID.
     *
     * @param string $value
     * @return $this
     */
    public function setMilitaryID($value)
    {
        return $this->setParameter('militaryId', $value);
    }

    /**
     * Get the ach customer ID.
     *
     * @return string
     */
    public function getCustomer()
    {
        return $this->getParameter('customer');
    }

    /**
     * Sets the ach customer ID.
     *
     * @param string $value
     * @return $this
     */
    public function setCustomer($value)
    {
        return $this->setParameter('customer', $value);
    }

    /**
     * Get the ach e-comerce flag.
     *
     * @return string
     */
    public function getEcommerceFlag()
    {
        return $this->getParameter('ecommerceFlag');
    }

    /**
     * Sets the ach e-comerce flag.
     *
     * @param string $value
     * @return $this
     */
    public function setEcommerceFlag($value)
    {
        return $this->setParameter('ecommerceFlag', $value);
    }

    /**
     * Get the ach vip.
     *
     * @return string
     */
    public function getVIP()
    {
        return $this->getParameter('vip');
    }

    /**
     * Sets the ach vip.
     *
     * @param string $value
     * @return $this
     */
    public function setVIP($value)
    {
        return $this->setParameter('vip', $value);
    }

    /**
     * Get the ach release Type.
     *
     * @return string
     */
    public function getReleaseType()
    {
        return $this->getParameter('releaseType');
    }

    /**
     * Sets the ach release Type.
     *
     * @param string $value
     * @return $this
     */
    public function setReleaseType($value)
    {
        return $this->setParameter('releaseType', $value);
    }

    /**
     * Get the ach clerk.
     *
     * @return string
     */
    public function getClerk()
    {
        return $this->getParameter('clerk');
    }

    /**
     * Sets the ach clerk.
     *
     * @param string $value
     * @return $this
     */
    public function setClerk($value)
    {
        return $this->setParameter('clerk', $value);
    }

    /**
     * Get the ach device.
     *
     * @return string
     */
    public function getDevice()
    {
        return $this->getParameter('device');
    }

    /**
     * Sets the ach Device.
     *
     * @param string $value
     * @return $this
     */
    public function setDevice($value)
    {
        return $this->setParameter('device', $value);
    }

    /**
     * Get the ach micr.
     *
     * @return string
     */
    public function getMicr()
    {
        return $this->getParameter('micr');
    }

    /**
     * Sets the ach micr.
     *
     * @param string $value
     * @return $this
     */
    public function setMicr($value)
    {
        return $this->setParameter('micr', $value);
    }

    /**
     * Get Ach Title.
     *
     * @return string
     */
    public function getTitle()
    {
        return $this->getBillingTitle();
    }

    /**
     * Set Ach Title.
     *
     * @param string $value Parameter value
     * @return $this
     */
    public function setTitle($value)
    {
        $this->setBillingTitle($value);
        $this->setShippingTitle($value);

        return $this;
    }

    /**
     * Get Ach First Name.
     *
     * @return string
     */
    public function getFirstName()
    {
        return $this->getBillingFirstName();
    }

    /**
     * Set Ach First Name (Billing and Shipping).
     *
     * @param string $value Parameter value
     * @return $this
     */
    public function setFirstName($value)
    {
        $this->setBillingFirstName($value);
        $this->setShippingFirstName($value);

        return $this;
    }

    /**
     * Get Ach Last Name.
     *
     * @return string
     */
    public function getLastName()
    {
        return $this->getBillingLastName();
    }

    /**
     * Set Ach Last Name (Billing and Shipping).
     *
     * @param string $value Parameter value
     * @return $this
     */
    public function setLastName($value)
    {
        $this->setBillingLastName($value);
        $this->setShippingLastName($value);

        return $this;
    }

    /**
     * Get Ach Name.
     *
     * @return string
     */
    public function getName()
    {
        return $this->getBillingName();
    }

    /**
     * Set Ach Name (Billing and Shipping).
     *
     * @param string $value Parameter value
     * @return $this
     */
    public function setName($value)
    {
        $this->setBillingName($value);
        $this->setShippingName($value);

        return $this;
    }

    /**
     * Get the ach billing title.
     *
     * @return string
     */
    public function getBillingTitle()
    {
        return $this->getParameter('billingTitle');
    }

    /**
     * Sets the ach billing title.
     *
     * @param string $value
     * @return $this
     */
    public function setBillingTitle($value)
    {
        return $this->setParameter('billingTitle', $value);
    }

    /**
     * Get the ach billing name.
     *
     * @return string
     */
    public function getBillingName()
    {
        return trim($this->getBillingFirstName() . ' ' . $this->getBillingLastName());
    }

    /**
     * Split the full name in the first and last name.
     *
     * @param $fullName
     * @return array with first and lastname
     */
    protected function listFirstLastName($fullName)
    {
        $names = explode(' ', $fullName, 2);

        return [$names[0], isset($names[1]) ? $names[1] : null];
    }

    /**
     * Sets the ach billing name.
     *
     * @param string $value
     * @return $this
     */
    public function setBillingName($value)
    {
        $names = $this->listFirstLastName($value);

        $this->setBillingFirstName($names[0]);
        $this->setBillingLastName($names[1]);

        return $this;
    }

    /**
     * Get the first part of the ach billing name.
     *
     * @return string
     */
    public function getBillingFirstName()
    {
        return $this->getParameter('billingFirstName');
    }

    /**
     * Sets the first part of the ach billing name.
     *
     * @param string $value
     * @return $this
     */
    public function setBillingFirstName($value)
    {
        return $this->setParameter('billingFirstName', $value);
    }

    /**
     * Get the last part of the ach billing name.
     *
     * @return string
     */
    public function getBillingLastName()
    {
        return $this->getParameter('billingLastName');
    }

    /**
     * Sets the last part of the ach billing name.
     *
     * @param string $value
     * @return $this
     */
    public function setBillingLastName($value)
    {
        return $this->setParameter('billingLastName', $value);
    }

    /**
     * Get the billing company name.
     *
     * @return string
     */
    public function getBillingCompany()
    {
        return $this->getParameter('billingCompany');
    }

    /**
     * Sets the billing company name.
     *
     * @param string $value
     * @return $this
     */
    public function setBillingCompany($value)
    {
        return $this->setParameter('billingCompany', $value);
    }

    /**
     * Get the billing address, line 1.
     *
     * @return string
     */
    public function getBillingAddress1()
    {
        return $this->getParameter('billingAddress1');
    }

    /**
     * Sets the billing address, line 1.
     *
     * @param string $value
     * @return $this
     */
    public function setBillingAddress1($value)
    {
        return $this->setParameter('billingAddress1', $value);
    }

    /**
     * Get the billing address, line 2.
     *
     * @return string
     */
    public function getBillingAddress2()
    {
        return $this->getParameter('billingAddress2');
    }

    /**
     * Sets the billing address, line 2.
     *
     * @param string $value
     * @return $this
     */
    public function setBillingAddress2($value)
    {
        return $this->setParameter('billingAddress2', $value);
    }

    /**
     * Get the billing city.
     *
     * @return string
     */
    public function getBillingCity()
    {
        return $this->getParameter('billingCity');
    }

    /**
     * Sets billing city.
     *
     * @param string $value
     * @return $this
     */
    public function setBillingCity($value)
    {
        return $this->setParameter('billingCity', $value);
    }

    /**
     * Get the billing postcode.
     *
     * @return string
     */
    public function getBillingPostcode()
    {
        return $this->getParameter('billingPostcode');
    }

    /**
     * Sets the billing postcode.
     *
     * @param string $value
     * @return $this
     */
    public function setBillingPostcode($value)
    {
        return $this->setParameter('billingPostcode', $value);
    }

    /**
     * Get the billing state.
     *
     * @return string
     */
    public function getBillingState()
    {
        return $this->getParameter('billingState');
    }

    /**
     * Sets the billing state.
     *
     * @param string $value
     * @return $this
     */
    public function setBillingState($value)
    {
        return $this->setParameter('billingState', $value);
    }

    /**
     * Get the billing country name.
     *
     * @return string
     */
    public function getBillingCountry()
    {
        return $this->getParameter('billingCountry');
    }

    /**
     * Sets the billing country name.
     *
     * @param string $value
     * @return $this
     */
    public function setBillingCountry($value)
    {
        return $this->setParameter('billingCountry', $value);
    }

    /**
     * Get the billing phone number.
     *
     * @return string
     */
    public function getBillingPhone()
    {
        return $this->getParameter('billingPhone');
    }

    /**
     * Sets the billing phone number.
     *
     * @param string $value
     * @return $this
     */
    public function setBillingPhone($value)
    {
        return $this->setParameter('billingPhone', $value);
    }

    /**
     * Get the billing phone number extension.
     *
     * @return string
     */
    public function getBillingPhoneExtension()
    {
        return $this->getParameter('billingPhoneExtension');
    }

    /**
     * Sets the billing phone number extension.
     *
     * @param string $value
     * @return $this
     */
    public function setBillingPhoneExtension($value)
    {
        return $this->setParameter('billingPhoneExtension', $value);
    }

    /**
     * Get the billing fax number.
     *
     * @return string
     */
    public function getBillingFax()
    {
        return $this->getParameter('billingFax');
    }

    /**
     * Sets the billing fax number.
     *
     * @param string $value
     * @return $this
     */
    public function setBillingFax($value)
    {
        return $this->setParameter('billingFax', $value);
    }

    /**
     * Get the title of the ach shipping name.
     *
     * @return string
     */
    public function getShippingTitle()
    {
        return $this->getParameter('shippingTitle');
    }

    /**
     * Sets the title of the ach shipping name.
     *
     * @param string $value
     * @return $this
     */
    public function setShippingTitle($value)
    {
        return $this->setParameter('shippingTitle', $value);
    }

    /**
     * Get the ach shipping name.
     *
     * @return string
     */
    public function getShippingName()
    {
        return trim($this->getShippingFirstName() . ' ' . $this->getShippingLastName());
    }

    /**
     * Sets the ach shipping name.
     *
     * @param string $value
     * @return $this
     */
    public function setShippingName($value)
    {
        $names = $this->listFirstLastName($value);

        $this->setShippingFirstName($names[0]);
        $this->setShippingLastName($names[1]);

        return $this;
    }

    /**
     * Get the first part of the ach shipping name.
     *
     * @return string
     */
    public function getShippingFirstName()
    {
        return $this->getParameter('shippingFirstName');
    }

    /**
     * Sets the first part of the ach shipping name.
     *
     * @param string $value
     * @return $this
     */
    public function setShippingFirstName($value)
    {
        return $this->setParameter('shippingFirstName', $value);
    }

    /**
     * Get the last part of the ach shipping name.
     *
     * @return string
     */
    public function getShippingLastName()
    {
        return $this->getParameter('shippingLastName');
    }

    /**
     * Sets the last part of the ach shipping name.
     *
     * @param string $value
     * @return $this
     */
    public function setShippingLastName($value)
    {
        return $this->setParameter('shippingLastName', $value);
    }

    /**
     * Get the shipping company name.
     *
     * @return string
     */
    public function getShippingCompany()
    {
        return $this->getParameter('shippingCompany');
    }

    /**
     * Sets the shipping company name.
     *
     * @param string $value
     * @return $this
     */
    public function setShippingCompany($value)
    {
        return $this->setParameter('shippingCompany', $value);
    }

    /**
     * Get the shipping address, line 1.
     *
     * @return string
     */
    public function getShippingAddress1()
    {
        return $this->getParameter('shippingAddress1');
    }

    /**
     * Sets the shipping address, line 1.
     *
     * @param string $value
     * @return $this
     */
    public function setShippingAddress1($value)
    {
        return $this->setParameter('shippingAddress1', $value);
    }

    /**
     * Get the shipping address, line 2.
     *
     * @return string
     */
    public function getShippingAddress2()
    {
        return $this->getParameter('shippingAddress2');
    }

    /**
     * Sets the shipping address, line 2.
     *
     * @param string $value
     * @return $this
     */
    public function setShippingAddress2($value)
    {
        return $this->setParameter('shippingAddress2', $value);
    }

    /**
     * Get the shipping city.
     *
     * @return string
     */
    public function getShippingCity()
    {
        return $this->getParameter('shippingCity');
    }

    /**
     * Sets the shipping city.
     *
     * @param string $value
     * @return $this
     */
    public function setShippingCity($value)
    {
        return $this->setParameter('shippingCity', $value);
    }

    /**
     * Get the shipping postcode.
     *
     * @return string
     */
    public function getShippingPostcode()
    {
        return $this->getParameter('shippingPostcode');
    }

    /**
     * Sets the shipping postcode.
     *
     * @param string $value
     * @return $this
     */
    public function setShippingPostcode($value)
    {
        return $this->setParameter('shippingPostcode', $value);
    }

    /**
     * Get the shipping state.
     *
     * @return string
     */
    public function getShippingState()
    {
        return $this->getParameter('shippingState');
    }

    /**
     * Sets the shipping state.
     *
     * @param string $value
     * @return $this
     */
    public function setShippingState($value)
    {
        return $this->setParameter('shippingState', $value);
    }

    /**
     * Get the shipping country.
     *
     * @return string
     */
    public function getShippingCountry()
    {
        return $this->getParameter('shippingCountry');
    }

    /**
     * Sets the shipping country.
     *
     * @param string $value
     * @return $this
     */
    public function setShippingCountry($value)
    {
        return $this->setParameter('shippingCountry', $value);
    }

    /**
     * Get the shipping phone number.
     *
     * @return string
     */
    public function getShippingPhone()
    {
        return $this->getParameter('shippingPhone');
    }

    /**
     * Sets the shipping phone number.
     *
     * @param string $value
     * @return $this
     */
    public function setShippingPhone($value)
    {
        return $this->setParameter('shippingPhone', $value);
    }

    /**
     * Get the shipping phone number extension.
     *
     * @return string
     */
    public function getShippingPhoneExtension()
    {
        return $this->getParameter('shippingPhoneExtension');
    }

    /**
     * Sets the shipping phone number extension.
     *
     * @param string $value
     * @return $this
     */
    public function setShippingPhoneExtension($value)
    {
        return $this->setParameter('shippingPhoneExtension', $value);
    }

    /**
     * Get the shipping fax number.
     *
     * @return string
     */
    public function getShippingFax()
    {
        return $this->getParameter('shippingFax');
    }

    /**
     * Sets the shipping fax number.
     *
     * @param string $value
     * @return $this
     */
    public function setShippingFax($value)
    {
        return $this->setParameter('shippingFax', $value);
    }

    /**
     * Get the billing address, line 1.
     *
     * @return string
     */
    public function getAddress1()
    {
        return $this->getParameter('billingAddress1');
    }

    /**
     * Sets the billing and shipping address, line 1.
     *
     * @param string $value
     * @return $this
     */
    public function setAddress1($value)
    {
        $this->setParameter('billingAddress1', $value);
        $this->setParameter('shippingAddress1', $value);

        return $this;
    }

    /**
     * Get the billing address, line 2.
     *
     * @return string
     */
    public function getAddress2()
    {
        return $this->getParameter('billingAddress2');
    }

    /**
     * Sets the billing and shipping address, line 2.
     *
     * @param string $value
     * @return $this
     */
    public function setAddress2($value)
    {
        $this->setParameter('billingAddress2', $value);
        $this->setParameter('shippingAddress2', $value);

        return $this;
    }

    /**
     * Get the billing city.
     *
     * @return string
     */
    public function getCity()
    {
        return $this->getParameter('billingCity');
    }

    /**
     * Sets the billing and shipping city.
     *
     * @param string $value
     * @return $this
     */
    public function setCity($value)
    {
        $this->setParameter('billingCity', $value);
        $this->setParameter('shippingCity', $value);

        return $this;
    }

    /**
     * Get the billing postcode.
     *
     * @return string
     */
    public function getPostcode()
    {
        return $this->getParameter('billingPostcode');
    }

    /**
     * Sets the billing and shipping postcode.
     *
     * @param string $value
     * @return $this
     */
    public function setPostcode($value)
    {
        $this->setParameter('billingPostcode', $value);
        $this->setParameter('shippingPostcode', $value);

        return $this;
    }

    /**
     * Get the billing state.
     *
     * @return string
     */
    public function getState()
    {
        return $this->getParameter('billingState');
    }

    /**
     * Sets the billing and shipping state.
     *
     * @param string $value
     * @return $this
     */
    public function setState($value)
    {
        $this->setParameter('billingState', $value);
        $this->setParameter('shippingState', $value);

        return $this;
    }

    /**
     * Get the billing country.
     *
     * @return string
     */
    public function getCountry()
    {
        return $this->getParameter('billingCountry');
    }

    /**
     * Sets the billing and shipping country.
     *
     * @param string $value
     * @return $this
     */
    public function setCountry($value)
    {
        $this->setParameter('billingCountry', $value);
        $this->setParameter('shippingCountry', $value);

        return $this;
    }

    /**
     * Get the billing phone number.
     *
     * @return string
     */
    public function getPhone()
    {
        return $this->getParameter('billingPhone');
    }

    /**
     * Sets the billing and shipping phone number.
     *
     * @param string $value
     * @return $this
     */
    public function setPhone($value)
    {
        $this->setParameter('billingPhone', $value);
        $this->setParameter('shippingPhone', $value);

        return $this;
    }

    /**
     * Get the billing phone number extension.
     *
     * @return string
     */
    public function getPhoneExtension()
    {
        return $this->getParameter('billingPhoneExtension');
    }

    /**
     * Sets the billing and shipping phone number extension.
     *
     * @param string $value
     * @return $this
     */
    public function setPhoneExtension($value)
    {
        $this->setParameter('billingPhoneExtension', $value);
        $this->setParameter('shippingPhoneExtension', $value);

        return $this;
    }

    /**
     * Get the billing fax number..
     *
     * @return string
     */
    public function getFax()
    {
        return $this->getParameter('billingFax');
    }

    /**
     * Sets the billing and shipping fax number.
     *
     * @param string $value
     * @return $this
     */
    public function setFax($value)
    {
        $this->setParameter('billingFax', $value);
        $this->setParameter('shippingFax', $value);

        return $this;
    }

    /**
     * Get the ach billing company name.
     *
     * @return string
     */
    public function getCompany()
    {
        return $this->getParameter('billingCompany');
    }

    /**
     * Sets the billing and shipping company name.
     *
     * @param string $value
     * @return $this
     */
    public function setCompany($value)
    {
        $this->setParameter('billingCompany', $value);
        $this->setParameter('shippingCompany', $value);

        return $this;
    }

    /**
     * Get the achholder's email address.
     *
     * @return string
     */
    public function getEmail()
    {
        return $this->getParameter('email');
    }

    /**
     * Sets the achholder's email address.
     *
     * @param string $value
     * @return $this
     */
    public function setEmail($value)
    {
        return $this->setParameter('email', $value);
    }

    /**
     * Get the achholder's birthday.
     *
     * @return string
     */
    public function getBirthday($format = 'Y-m-d')
    {
        $value = $this->getParameter('birthday');

        return $value ? $value->format($format) : null;
    }

    /**
     * Sets the achholder's birthday.
     *
     * @param string $value
     * @return $this
     */
    public function setBirthday($value)
    {
        if ($value) {
            $value = new DateTime($value, new DateTimeZone('UTC'));
        } else {
            $value = null;
        }

        return $this->setParameter('birthday', $value);
    }

    /**
     * Get the achholder's gender.
     *
     * @return string
     */
    public function getGender()
    {
        return $this->getParameter('gender');
    }

    /**
     * Sets the achholder's gender.
     *
     * @param string $value
     * @return $this
     */
    public function setGender($value)
    {
        return $this->setParameter('gender', $value);
    }
}
