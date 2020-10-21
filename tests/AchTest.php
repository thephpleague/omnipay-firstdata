<?php

namespace Omnipay\FirstData;

use Omnipay\Common\Exception\InvalidRequestException;
use Omnipay\Tests\TestCase;

class AchTest extends TestCase
{
    /** @var Ach */
    private $ach;

    public function setUp()
    {
        // [
        //     'firstName'            => 'Example',
        //     'lastName'             => 'Customer',
        //     'routingNumber'        => '021000021',
        //     'accountNumber'        => '2020',
        //     'checkNumber'          => '123',
        // ]
        $this->ach = new Ach;
        $this->ach->setRoutingNumber('021000021');
        $this->ach->setAccountNumber('2020');
        $this->ach->setFirstName('Example');
        $this->ach->setLastName('Customer');
        $this->ach->setCheckNumber('123');
    }

    public function testConstructWithParams()
    {
        $ach = new Ach(array('name' => 'Test Customer'));
        $this->assertSame('Test Customer', $ach->getName());
    }

    public function testInitializeWithParams()
    {
        $ach = new Ach;
        $ach->initialize(array('name' => 'Test Customer'));
        $this->assertSame('Test Customer', $ach->getName());
    }

    public function testGetParamters()
    {
        $ach = new Ach(array(
            'name' => 'Example Customer',
            'checkNumber' => '123',
        ));

        $parameters = $ach->getParameters();
        $this->assertSame('Example', $parameters['billingFirstName']);
        $this->assertSame('Customer', $parameters['billingLastName']);
        $this->assertSame('123', $parameters['checkNumber']);
    }

    /**
     * @doesNotPerformAssertions
     */
    public function testValidateFixture()
    {
        $this->ach->validate();
    }

    /**
     * @expectedException \Omnipay\FirstData\Exception\InvalidAchException
     * @expectedExceptionMessage The routing number is required
     */
    public function testValidateRoutingNumberRequired()
    {
        $this->ach->setRoutingNumber(null);
        $this->ach->validate();
    }

    /**
     * @expectedException \Omnipay\FirstData\Exception\InvalidAchException
     * @expectedExceptionMessage The account number is required
     */
    public function testValidateAccountNumberRequired()
    {
        $this->ach->setAccountNumber(null);
        $this->ach->validate();
    }

    /**
     * @expectedException \Omnipay\FirstData\Exception\InvalidAchException
     * @expectedExceptionMessage The first name is required
     */
    public function testValidateFirstNameRequired()
    {
        $this->ach->setFirstName(null);
        $this->ach->validate();
    }

    /**
     * @expectedException \Omnipay\FirstData\Exception\InvalidAchException
     * @expectedExceptionMessage The last name is required
     */
    public function testValidateLastNameRequired()
    {
        $this->ach->setLastName(null);
        $this->ach->validate();
    }

    /**
     * @expectedException \Omnipay\FirstData\Exception\InvalidAchException
     * @expectedExceptionMessage Routing Number is invalid
     */
    public function testValidateRoutingNumber()
    {
        $this->ach->setRoutingNumber('021000020');
        $this->ach->validate();
    }

    public function testTitle()
    {
        $this->ach->setTitle('Mr.');
        $this->assertEquals('Mr.', $this->ach->getTitle());
    }

    public function testFirstName()
    {
        $this->ach->setFirstName('Bob');
        $this->assertEquals('Bob', $this->ach->getFirstName());
    }

    public function testLastName()
    {
        $this->ach->setLastName('Smith');
        $this->assertEquals('Smith', $this->ach->getLastName());
    }

    public function testGetName()
    {
        $this->ach->setFirstName('Bob');
        $this->ach->setLastName('Smith');
        $this->assertEquals('Bob Smith', $this->ach->getName());
    }

    public function testSetName()
    {
        $this->ach->setName('Bob Smith');
        $this->assertEquals('Bob', $this->ach->getFirstName());
        $this->assertEquals('Smith', $this->ach->getLastName());
    }

    public function testSetNameWithOneName()
    {
        $this->ach->setName('Bob');
        $this->assertEquals('Bob', $this->ach->getFirstName());
        $this->assertEquals('', $this->ach->getLastName());
    }

    public function testSetNameWithMultipleNames()
    {
        $this->ach->setName('Bob John Smith');
        $this->assertEquals('Bob', $this->ach->getFirstName());
        $this->assertEquals('John Smith', $this->ach->getLastName());
    }

    public function testBillingTitle()
    {
        $this->ach->setBillingTitle('Mrs.');
        $this->assertEquals('Mrs.', $this->ach->getBillingTitle());
        $this->assertEquals('Mrs.', $this->ach->getTitle());
    }

    public function testBillingFirstName()
    {
        $this->ach->setBillingFirstName('Bob');
        $this->assertEquals('Bob', $this->ach->getBillingFirstName());
        $this->assertEquals('Bob', $this->ach->getFirstName());
    }

    public function testBillingLastName()
    {
        $this->ach->setBillingLastName('Smith');
        $this->assertEquals('Smith', $this->ach->getBillingLastName());
        $this->assertEquals('Smith', $this->ach->getLastName());
    }

    public function testBillingName()
    {
        $this->ach->setBillingFirstName('Bob');
        $this->ach->setBillingLastName('Smith');
        $this->assertEquals('Bob Smith', $this->ach->getBillingName());

        $this->ach->setBillingName('John Foo');
        $this->assertEquals('John', $this->ach->getBillingFirstName());
        $this->assertEquals('Foo', $this->ach->getBillingLastName());
    }

    public function testBillingCompany()
    {
        $this->ach->setBillingCompany('SuperSoft');
        $this->assertEquals('SuperSoft', $this->ach->getBillingCompany());
        $this->assertEquals('SuperSoft', $this->ach->getCompany());
    }

    public function testBillingAddress1()
    {
        $this->ach->setBillingAddress1('31 Spooner St');
        $this->assertEquals('31 Spooner St', $this->ach->getBillingAddress1());
        $this->assertEquals('31 Spooner St', $this->ach->getAddress1());
    }

    public function testBillingAddress2()
    {
        $this->ach->setBillingAddress2('Suburb');
        $this->assertEquals('Suburb', $this->ach->getBillingAddress2());
        $this->assertEquals('Suburb', $this->ach->getAddress2());
    }

    public function testBillingCity()
    {
        $this->ach->setBillingCity('Quahog');
        $this->assertEquals('Quahog', $this->ach->getBillingCity());
        $this->assertEquals('Quahog', $this->ach->getCity());
    }

    public function testBillingPostcode()
    {
        $this->ach->setBillingPostcode('12345');
        $this->assertEquals('12345', $this->ach->getBillingPostcode());
        $this->assertEquals('12345', $this->ach->getPostcode());
    }

    public function testBillingState()
    {
        $this->ach->setBillingState('RI');
        $this->assertEquals('RI', $this->ach->getBillingState());
        $this->assertEquals('RI', $this->ach->getState());
    }

    public function testBillingCountry()
    {
        $this->ach->setBillingCountry('US');
        $this->assertEquals('US', $this->ach->getBillingCountry());
        $this->assertEquals('US', $this->ach->getCountry());
    }

    public function testBillingPhone()
    {
        $this->ach->setBillingPhone('12345');
        $this->assertSame('12345', $this->ach->getBillingPhone());
        $this->assertSame('12345', $this->ach->getPhone());
    }

    public function testBillingPhoneExtension()
    {
        $this->ach->setBillingPhoneExtension('001');
        $this->assertSame('001', $this->ach->getBillingPhoneExtension());
        $this->assertSame('001', $this->ach->getPhoneExtension());
    }

    public function testBillingFax()
    {
        $this->ach->setBillingFax('54321');
        $this->assertSame('54321', $this->ach->getBillingFax());
        $this->assertSame('54321', $this->ach->getFax());
    }

    public function testShippingTitle()
    {
        $this->ach->setShippingTitle('Dr.');
        $this->assertEquals('Dr.', $this->ach->getShippingTitle());
    }

    public function testShippingFirstName()
    {
        $this->ach->setShippingFirstName('James');
        $this->assertEquals('James', $this->ach->getShippingFirstName());
    }

    public function testShippingLastName()
    {
        $this->ach->setShippingLastName('Doctor');
        $this->assertEquals('Doctor', $this->ach->getShippingLastName());
    }

    public function testShippingName()
    {
        $this->ach->setShippingFirstName('Bob');
        $this->ach->setShippingLastName('Smith');
        $this->assertEquals('Bob Smith', $this->ach->getShippingName());

        $this->ach->setShippingName('John Foo');
        $this->assertEquals('John', $this->ach->getShippingFirstName());
        $this->assertEquals('Foo', $this->ach->getShippingLastName());
    }

    public function testShippingCompany()
    {
        $this->ach->setShippingCompany('SuperSoft');
        $this->assertEquals('SuperSoft', $this->ach->getShippingCompany());
    }

    public function testShippingAddress1()
    {
        $this->ach->setShippingAddress1('31 Spooner St');
        $this->assertEquals('31 Spooner St', $this->ach->getShippingAddress1());
    }

    public function testShippingAddress2()
    {
        $this->ach->setShippingAddress2('Suburb');
        $this->assertEquals('Suburb', $this->ach->getShippingAddress2());
    }

    public function testShippingCity()
    {
        $this->ach->setShippingCity('Quahog');
        $this->assertEquals('Quahog', $this->ach->getShippingCity());
    }

    public function testShippingPostcode()
    {
        $this->ach->setShippingPostcode('12345');
        $this->assertEquals('12345', $this->ach->getShippingPostcode());
    }

    public function testShippingState()
    {
        $this->ach->setShippingState('RI');
        $this->assertEquals('RI', $this->ach->getShippingState());
    }

    public function testShippingCountry()
    {
        $this->ach->setShippingCountry('US');
        $this->assertEquals('US', $this->ach->getShippingCountry());
    }

    public function testShippingPhone()
    {
        $this->ach->setShippingPhone('12345');
        $this->assertEquals('12345', $this->ach->getShippingPhone());
    }

    public function testShippingPhoneExtension()
    {
        $this->ach->setShippingPhoneExtension('001');
        $this->assertEquals('001', $this->ach->getShippingPhoneExtension());
    }

    public function testShippingFax()
    {
        $this->ach->setShippingFax('54321');
        $this->assertEquals('54321', $this->ach->getShippingFax());
    }

    public function testCompany()
    {
        $this->ach->setCompany('FooBar');
        $this->assertEquals('FooBar', $this->ach->getCompany());
        $this->assertEquals('FooBar', $this->ach->getBillingCompany());
        $this->assertEquals('FooBar', $this->ach->getShippingCompany());
    }

    public function testAddress1()
    {
        $this->ach->setAddress1('31 Spooner St');
        $this->assertEquals('31 Spooner St', $this->ach->getAddress1());
        $this->assertEquals('31 Spooner St', $this->ach->getBillingAddress1());
        $this->assertEquals('31 Spooner St', $this->ach->getShippingAddress1());
    }

    public function testAddress2()
    {
        $this->ach->setAddress2('Suburb');
        $this->assertEquals('Suburb', $this->ach->getAddress2());
        $this->assertEquals('Suburb', $this->ach->getBillingAddress2());
        $this->assertEquals('Suburb', $this->ach->getShippingAddress2());
    }

    public function testCity()
    {
        $this->ach->setCity('Quahog');
        $this->assertEquals('Quahog', $this->ach->getCity());
        $this->assertEquals('Quahog', $this->ach->getBillingCity());
        $this->assertEquals('Quahog', $this->ach->getShippingCity());
    }

    public function testPostcode()
    {
        $this->ach->setPostcode('12345');
        $this->assertEquals('12345', $this->ach->getPostcode());
        $this->assertEquals('12345', $this->ach->getBillingPostcode());
        $this->assertEquals('12345', $this->ach->getShippingPostcode());
    }

    public function testState()
    {
        $this->ach->setState('RI');
        $this->assertEquals('RI', $this->ach->getState());
        $this->assertEquals('RI', $this->ach->getBillingState());
        $this->assertEquals('RI', $this->ach->getShippingState());
    }

    public function testCountry()
    {
        $this->ach->setCountry('US');
        $this->assertEquals('US', $this->ach->getCountry());
        $this->assertEquals('US', $this->ach->getBillingCountry());
        $this->assertEquals('US', $this->ach->getShippingCountry());
    }

    public function testPhone()
    {
        $this->ach->setPhone('12345');
        $this->assertEquals('12345', $this->ach->getPhone());
        $this->assertEquals('12345', $this->ach->getBillingPhone());
        $this->assertEquals('12345', $this->ach->getShippingPhone());
    }

    public function testPhoneExtension()
    {
        $this->ach->setPhoneExtension('001');
        $this->assertEquals('001', $this->ach->getPhoneExtension());
        $this->assertEquals('001', $this->ach->getBillingPhoneExtension());
        $this->assertEquals('001', $this->ach->getShippingPhoneExtension());
    }

    public function testFax()
    {
        $this->ach->setFax('54321');
        $this->assertEquals('54321', $this->ach->getFax());
        $this->assertEquals('54321', $this->ach->getBillingFax());
        $this->assertEquals('54321', $this->ach->getShippingFax());
    }

    public function testEmail()
    {
        $this->ach->setEmail('adrian@example.com');
        $this->assertEquals('adrian@example.com', $this->ach->getEmail());
    }

    public function testBirthday()
    {
        $this->ach->setBirthday('01-02-2000');
        $this->assertEquals('2000-02-01', $this->ach->getBirthday());
        $this->assertEquals('01/02/2000', $this->ach->getBirthday('d/m/Y'));
    }

    public function testBirthdayEmpty()
    {
        $this->ach->setBirthday('');
        $this->assertNull($this->ach->getBirthday());
    }

    public function testGender()
    {
        $this->ach->setGender('female');
        $this->assertEquals('female', $this->ach->getGender());
    }

    /**
     * Ach Specific starts here
     */
    public function testCheckNumber()
    {
        $this->ach->setCheckNumber('123');
        $this->assertEquals('123', $this->ach->getCheckNumber());

        $ach = new Ach(array('checkNumber' => '123'));
        $this->assertEquals('123', $ach->getCheckNumber());
    }

    public function testCheckType()
    {
        $this->ach->setCheckType('P');
        $this->assertEquals('P', $this->ach->getCheckType());

        $ach = new Ach(array('checkType' => 'P'));
        $this->assertEquals('P', $ach->getCheckType());
    }

    public function testReleaseType()
    {
        $this->ach->setReleaseType('R');
        $this->assertEquals('R', $this->ach->getReleaseType());

        $ach = new Ach(array('releaseType' => 'R'));
        $this->assertEquals('R', $ach->getReleaseType());
    }

    public function testVIP()
    {
        $this->ach->setVIP(true);
        $this->assertEquals(true, $this->ach->getVIP());

        $ach = new Ach(array('vip' => true));
        $this->assertEquals(true, $ach->getVIP());
    }

    public function testClerk()
    {
        $this->ach->setClerk("ABCD");
        $this->assertEquals("ABCD", $this->ach->getClerk());

        $ach = new Ach(array('clerk' => "ABCD"));
        $this->assertEquals("ABCD", $ach->getClerk());
    }

    public function testDevice()
    {
        $this->ach->setDevice("ABCD");
        $this->assertEquals("ABCD", $this->ach->getDevice());

        $ach = new Ach(array('device' => "ABCD"));
        $this->assertEquals("ABCD", $ach->getDevice());
    }

    public function testMicr()
    {
        $this->ach->setMicr("ABCD");
        $this->assertEquals("ABCD", $this->ach->getMicr());

        $ach = new Ach(array('micr' => "ABCD"));
        $this->assertEquals("ABCD", $ach->getMicr());
    }

    public function testEcommerceFlag()
    {
        $this->ach->setEcommerceFlag(7);
        $this->assertEquals(7, $this->ach->getEcommerceFlag());

        $ach = new Ach(array('ecommerce_flag' => 7));
        $this->assertEquals(7, $ach->getEcommerceFlag());
    }

    public function testDriversLicense()
    {
        $this->ach->setLicense("123ABC");
        $this->assertEquals("123ABC", $this->ach->getLicense());

        $ach = new Ach(array('license' => "123ABC"));
        $this->assertEquals("123ABC", $ach->getLicense());
    }

    public function testDriversLicenseState()
    {
        $this->ach->setLicenseState("PA");
        $this->assertEquals("PA", $this->ach->getLicenseState());

        $ach = new Ach(array('license_state' => "PA"));
        $this->assertEquals("PA", $ach->getLicenseState());
    }

    public function testSSN()
    {
        $this->ach->setSSN("123ABC");
        $this->assertEquals("123ABC", $this->ach->getSSN());

        $this->ach->setSocialSecurityNumber("123ABC");
        $this->assertEquals("123ABC", $this->ach->getSocialSecurityNumber());

        $ach = new Ach(array('ssn' => "123ABC"));
        $this->assertEquals("123ABC", $ach->getSSN());
        $this->assertEquals("123ABC", $ach->getSocialSecurityNumber());

    }

    public function testTaxID()
    {
        $this->ach->setTaxID("123ABC");
        $this->assertEquals("123ABC", $this->ach->getTaxID());

        $ach = new Ach(array('taxId' => "123ABC"));
        $this->assertEquals("123ABC", $ach->getTaxID());
    }

    public function testMilitaryID()
    {
        $this->ach->setMilitaryID("123ABC");
        $this->assertEquals("123ABC", $this->ach->getMilitaryID());

        $ach = new Ach(array('militaryId' => "123ABC"));
        $this->assertEquals("123ABC", $ach->getMilitaryID());
    }
}
