<?php
/**
 * First Data Payeezy Purchase Request
 */
namespace Omnipay\FirstData\Message;

use Omnipay\Common\Exception\InvalidRequestException;

/**
 * First Data Payeezy Purchase Request
 *
 * ### Example
 *
 * <code>
 * // Create a gateway for the First Data Payeezy Gateway
 * // (routes to GatewayFactory::create)
 * $gateway = Omnipay::create('FirstData_Payeezy');
 *
 * // Initialise the gateway
 * $gateway->initialize(array(
 *     'gatewayId' => '12341234',
 *     'password'  => 'thisISmyPASSWORD',
 *     'testMode'  => true, // Or false when you are ready for live transactions
 * ));
 *
 * // Create a credit card object
 * $card = new CreditCard(array(
 *     'firstName'            => 'Example',
 *     'lastName'             => 'Customer',
 *     'number'               => '4222222222222222',
 *     'expiryMonth'          => '01',
 *     'expiryYear'           => '2020',
 *     'cvv'                  => '123',
 *     'email'                => 'customer@example.com',
 *     'billingAddress1'      => '1 Scrubby Creek Road',
 *     'billingCountry'       => 'AU',
 *     'billingCity'          => 'Scrubby Creek',
 *     'billingPostcode'      => '4999',
 *     'billingState'         => 'QLD',
 * ));
 *
 * // Do a purchase transaction on the gateway
 * $transaction = $gateway->purchase(array(
 *     'description'              => 'Your order for widgets',
 *     'amount'                   => '10.00',
 *     'transactionId'            => 12345,
 *     'clientIp'                 => $_SERVER['REMOTE_ADDR'],
 *     'card'                     => $card,
 * ));
 *
 * // USE A TRANS-ARMOR TOKEN TO PROCESS A PURCHASE:
 *
 * // Create a credit card object
 * $card = new CreditCard(array(
 *     'firstName'            => 'Example',
 *     'lastName'             => 'Customer',
 *     'expiryMonth'          => '01',
 *     'expiryYear'           => '2020',
 * ));
 *
 * // Do a purchase transaction on the gateway
 * $transaction = $gateway->purchase(array(
 *     'description'              => 'Your order for widgets',
 *     'amount'                   => '10.00',
 *     'cardReference'            => $yourStoredToken,
 *     'clientIp'                 => $_SERVER['REMOTE_ADDR'],
 *     'card'                     => $card,
 *     'tokenCardType'              => 'visa', // MUST BE VALID CONST FROM \omnipay\common\CreditCard
 * ));
 *
 *
 *
 *
 * $response = $transaction->send();
 * if ($response->isSuccessful()) {
 *     echo "Purchase transaction was successful!\n";
 *     $sale_id = $response->getTransactionReference();
 *     echo "Transaction reference = " . $sale_id . "\n";
 * }
 * </code>
 */
class PayeezyPurchaseRequest extends PayeezyAbstractRequest
{

    protected $action = self::TRAN_PURCHASE;
    const CUSTOMER_ID_TYPE = [
        0 => "license",
        1 => "ssn",
        2 => "taxId",
        3 => "militaryId"
    ];

    public function getData()
    {
        $data = parent::getData();

        if($this->paymentMethod == "card"){
            return $this->getCardData($data);
        }else if ($this->paymentMethod == "ach" || $this->paymentMethod == "check"){
            return $this->getAchData($data);
        }
        throw new InvalidRequestException('Invalid Payment Method (Must be "card" or "check")');

    }

    protected function getCardData($data){
        $this->validate('amount', 'card');

        $data['amount'] = $this->getAmount();
        $data['currency_code'] = $this->getCurrency();
        $data['reference_no'] = $this->getTransactionId();

        // add credit card details
        if ($this->getCardReference()) {
            $this->validate('tokenCardType');
            $data['transarmor_token'] = $this->getCardReference();
            $data['credit_card_type'] = $this->getTokenCardType();
        } else {
            $this->getCard()->validate();
            $data['credit_card_type'] = self::getCardType($this->getCard()->getBrand());
            $data['cc_number'] = $this->getCard()->getNumber();

            $this->appendAVS($data);
            $this->appendCvv($data);
        }
        $data['cardholder_name'] = $this->getCard()->getName();
        $data['cc_expiry'] = $this->getCard()->getExpiryDate('my');

        $data['client_ip'] = $this->getClientIp();
        $data['client_email'] = $this->getCard()->getEmail();
        $data['language'] = strtoupper($this->getCard()->getCountry());

        return $data;
    }

    protected function getAchData($data){

        $this->validate('amount', 'ach');
        $this->getAch()->validate();

        $data['amount'] = $this->getAmount();
        $data['currency_code'] = $this->getCurrency();
        $data['reference_no'] = $this->getTransactionId();

        $data['account_number'] = $this->getAch()->getAccountNumber();
        $data['routing_number'] = $this->getAch()->getRoutingNumber();
        $data['cardholder_name'] = $this->getAch()->getName();

        $data['check_type'] = $this->getAch()->getCheckType();
        $data['check_number'] = $this->getAch()->getCheckNumber();


        $this->appendAch($data);
        $this->appendAchAuth($data);
        $this->appendAVS($data);

        $data['client_ip'] = $this->getClientIp();
        $data['client_email'] = $this->getAch()->getEmail();
        $data['language'] = strtoupper($this->getAch()->getCountry());

        return $data;
    }



    public function getTokenCardType()
    {
        return $this->getParameter('tokenCardType');
    }

    public function setTokenCardType($value)
    {
        return $this->setParameter('tokenCardType', $value);
    }

    protected function appendCvv(&$data){
        $data['cvd_presence_ind'] = 1;

        if($this->getApiVersion() <= 13){
            $data['cc_verification_str2'] = $this->getCard()->getCvv();
        }else{
            $data['cvd_code'] = $this->getCard()->getCvv();
        }
    }

    protected function appendAVS(&$data)
    {
        if($this->getApiVersion() <= 13){
            $data['cc_verification_str1'] = $this->getAVSHash();
        }else{
            $data['address'] = $this->getAddress();
        }
    }

    protected function appendAch(&$data){
        $data['release_type'] = $this->getAch()->getReleaseType();
        $data['vip'] = $this->getAch()->getVip();
        $data['clerk_id'] = $this->getAch()->getClerk();
        $data['device_id'] = $this->getAch()->getDevice();
        $data['micr'] = $this->getAch()->getMicr();
        $data['ecommerce_flag'] = $this->getAch()->getEcommerceFlag();
    }

    protected function appendAchAuth(&$data){
        $license = $this->getAch()->getLicense();
        if($license){
            $data['customer_id_type'] = 0;
            $data['customer_id_number'] = $license;
            return $data;
        }

        $SSN = $this->getAch()->getSSN();
        if($SSN){
            $data['customer_id_type'] = 1;
            $data['customer_id_number'] = $SSN;
            return $data;
        }

        $taxId = $this->getAch()->getTaxID();
        if($taxId){
            $data['customer_id_type'] = 2;
            $data['customer_id_number'] = $taxId;
            return $data;
        }

        $militaryId = $this->getAch()->getMilitaryId();
        if($militaryId){
            $data['customer_id_type'] = 3;
            $data['customer_id_number'] = $militaryId;
            return $data;
        }
    }
}

