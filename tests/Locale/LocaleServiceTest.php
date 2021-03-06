<?php

namespace Heystack\Ecommerce\Locale;

use Heystack\Ecommerce\Locale\Interfaces\CountryInterface;

class LocaleServiceTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var LocaleService
     */
    protected $localeService;
    
    protected function setUp()
    {
        $nzCountryIdentifier = $this->getMockBuilder('Heystack\Core\Identifier\Identifier')
            ->disableOriginalConstructor()
            ->getMock();
        $nzCountryIdentifier->expects($this->any())
            ->method('getFull')
            ->will($this->returnValue('NZ'));

        $nzCountry = $this->getMock('Heystack\Ecommerce\Locale\Interfaces\CountryInterface');
        $nzCountry->expects($this->any())
            ->method('getIdentifier')
            ->will($this->returnValue($nzCountryIdentifier));
        $nzCountry->expects($this->any())
            ->method('getCountryCode')
            ->will($this->returnValue('NZ'));
        $nzCountry->expects($this->any())
            ->method('isDefault')
            ->will($this->returnValue(true));
        $nzCountry->expects($this->any())
            ->method('getName')
            ->will($this->returnValue('New Zealand'));

        $ozCountryIdentifier = $this->getMockBuilder('Heystack\Core\Identifier\Identifier')
            ->disableOriginalConstructor()
            ->getMock();
        $ozCountryIdentifier->expects($this->any())
            ->method('getFull')
            ->will($this->returnValue('AU'));

        $ozCountry = $this->getMock('Heystack\Ecommerce\Locale\Interfaces\CountryInterface');
        $ozCountry->expects($this->any())
            ->method('getIdentifier')
            ->will($this->returnValue($ozCountryIdentifier));
        $ozCountry->expects($this->any())
            ->method('getCountryCode')
            ->will($this->returnValue('AU'));
        $ozCountry->expects($this->any())
            ->method('getName')
            ->will($this->returnValue('Oztralia'));

        $stateService = $this->getMockBuilder('Heystack\Core\State\State')
            ->disableOriginalConstructor()
            ->getMock();

        $eventDispatcher = $this->getMockBuilder('Symfony\Component\EventDispatcher\EventDispatcherInterface')
            ->disableOriginalConstructor()
            ->getMock();

        $this->localeService = new LocaleService([$nzCountry, $ozCountry], $nzCountry, $stateService, $eventDispatcher);
    }

    /**
     * Tears down the fixture, for example, closes a network connection.
     * This method is called after a test is executed.
     */
    protected function tearDown()
    {
        $this->localeService = null;
    }

    /**
     * @covers Heystack\Ecommerce\Locale\LocaleService::__construct
     * @covers Heystack\Ecommerce\Locale\LocaleService::setCountries
     * @covers Heystack\Ecommerce\Locale\LocaleService::addCountry
     * @covers Heystack\Ecommerce\Locale\LocaleService::getCountry
     * @covers Heystack\Ecommerce\Locale\LocaleService::saveState
     * @covers Heystack\Ecommerce\Locale\LocaleService::setActiveCountry
     * @covers Heystack\Ecommerce\Locale\LocaleService::getActiveCountry
     */
    public function testActiveCountryAccess()
    {
        $ozCountryIdentifier = $this->getMockBuilder('Heystack\Core\Identifier\Identifier')
            ->disableOriginalConstructor()
            ->getMock();
        $ozCountryIdentifier->expects($this->any())
            ->method('getFull')
            ->will($this->returnValue('AU'));

        $country = $this->localeService->getActiveCountry();
        $this->assertTrue($country instanceof CountryInterface);
        $this->assertEquals('New Zealand', $country->getName());

        $this->localeService->setActiveCountry($ozCountryIdentifier);

        $country = $this->localeService->getActiveCountry();
        $this->assertTrue($country instanceof CountryInterface);
        $this->assertEquals('Oztralia', $country->getName());

    }

    /**
     * @covers Heystack\Ecommerce\Locale\LocaleService::__construct
     * @covers Heystack\Ecommerce\Locale\LocaleService::setCountries
     * @covers Heystack\Ecommerce\Locale\LocaleService::addCountry
     * @covers Heystack\Ecommerce\Locale\LocaleService::getCountry
     */
    public function testGetCountry()
    {
        $nzCountryIdentifier = $this->getMockBuilder('Heystack\Core\Identifier\Identifier')
            ->disableOriginalConstructor()
            ->getMock();
        $nzCountryIdentifier->expects($this->any())
            ->method('getFull')
            ->will($this->returnValue('NZ'));

        $this->assertTrue($this->localeService->getCountry($nzCountryIdentifier) instanceof CountryInterface);
        $this->assertEquals('New Zealand', $this->localeService->getCountry($nzCountryIdentifier)->getName());
    }

    /**
     * @covers Heystack\Ecommerce\Locale\LocaleService::__construct
     * @covers Heystack\Ecommerce\Locale\LocaleService::setCountries
     * @covers Heystack\Ecommerce\Locale\LocaleService::addCountry
     * @covers Heystack\Ecommerce\Locale\LocaleService::getCountries
     */
    public function testGetCountries()
    {
        $this->assertContainsOnlyInstancesOf(
            'Heystack\Ecommerce\Locale\Interfaces\CountryInterface',
            $this->localeService->getCountries()
        );
    }

    /**
     * @covers Heystack\Ecommerce\Locale\LocaleService::__construct
     * @covers Heystack\Ecommerce\Locale\LocaleService::setCountries
     * @covers Heystack\Ecommerce\Locale\LocaleService::addCountry
     * @covers Heystack\Ecommerce\Locale\LocaleService::setDefaultCountry
     * @covers Heystack\Ecommerce\Locale\LocaleService::getDefaultCountry
     */
    public function testDefaultCountryAccess()
    {
        $ozCountryIdentifier = $this->getMockBuilder('Heystack\Core\Identifier\Identifier')
            ->disableOriginalConstructor()
            ->getMock();
        $ozCountryIdentifier->expects($this->any())
            ->method('getFull')
            ->will($this->returnValue('AU'));

        $country = $this->localeService->getDefaultCountry();
        $this->assertEquals('New Zealand', $country->getName());

        $this->assertTrue($this->localeService->setDefaultCountry($ozCountryIdentifier));
        $country = $this->localeService->getDefaultCountry();
        $this->assertEquals('Oztralia', $country->getName());

        $this->assertTrue($this->localeService->setDefaultCountry());
        $country = $this->localeService->getDefaultCountry();
        $this->assertEquals('New Zealand', $country->getName());
    }
}
