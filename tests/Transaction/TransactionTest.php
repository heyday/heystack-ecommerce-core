<?php

namespace Heystack\Ecommerce\Transaction;

use Heystack\Core\Storage\Backends\SilverStripeOrm\Backend;
use Heystack\Core\ViewableData\ViewableDataInterface;
use Heystack\Ecommerce\Transaction\Interfaces\TransactionModifierInterface;
use SebastianBergmann\Money\NZD;

class TransactionTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var Transaction
     */
    protected $transaction;

    /**
     * Sets up the fixture, for example, opens a network connection.
     * This method is called before a test is executed.
     */
    protected function setUp()
    {
        $currencyService = $this->getMockBuilder('Heystack\Ecommerce\Currency\CurrencyService')
            ->disableOriginalConstructor()
            ->getMock();
        $currencyService->expects($this->any())
            ->method('getZeroMoney')
            ->will($this->returnValue(new NZD(0)));
        $currencyService->expects($this->any())
            ->method('getActiveCurrencyCode')
            ->will($this->returnValue('NZD'));

        $stateService = $this->getMockBuilder('Heystack\Core\State\State')
            ->disableOriginalConstructor()
            ->getMock();

        $modifierIdentifier = $this->mockIdentifier('purchasableholder');

        $modifier = $this->getMock('Heystack\Ecommerce\Transaction\Interfaces\TransactionModifierInterface');
        $modifier->expects($this->any())
            ->method('getIdentifier')
            ->will($this->returnValue($modifierIdentifier));
        $modifier->expects($this->any())
            ->method('getType')
            ->will($this->returnValue('chargeable'));
        $modifier->expects($this->any())
            ->method('getTotal')
            ->will($this->returnValue(new NZD(100)));

        $this->transaction = new Transaction(
            $stateService,
            $currencyService,
            ['Pending', 'Successful', 'Failed', 'Dispatched', 'Processing', 'Cancelled'],
            'Pending'
        );

        $this->transaction->addModifier($modifier);
    }

    /**
     * Tears down the fixture, for example, closes a network connection.
     * This method is called after a test is executed.
     */
    protected function tearDown()
    {
        $this->transaction = null;
    }

    /**
     * Creates a mock Identifier that returns $value when 'getFull' is called.
     * @param $value
     */
    protected function mockIdentifier($value)
    {
        $identifier = $this->getMockBuilder('Heystack\Core\Identifier\Identifier')
            ->disableOriginalConstructor()
            ->getMock();
        $identifier->expects($this->any())
            ->method('getFull')
            ->will($this->returnValue($value));

        return $identifier;
    }


    /**
     * @covers Heystack\Ecommerce\Transaction\Transaction::__construct
     * @covers Heystack\Ecommerce\Transaction\Transaction::addModifier
     * @covers Heystack\Ecommerce\Transaction\Transaction::isValidStatus
     * @covers Heystack\Ecommerce\Transaction\Transaction::getModifier
     * @covers Heystack\Ecommerce\Transaction\Transaction::getModifiers
     * @covers Heystack\Ecommerce\Transaction\Transaction::getModifiersByType
     */
    public function testModifiersAccess()
    {
        $modifierIdentifier = $this->mockIdentifier('taxhandler');

        $modifier = $this->getMock('Heystack\Ecommerce\Transaction\Interfaces\TransactionModifierInterface');
        $modifier->expects($this->any())
            ->method('getIdentifier')
            ->will($this->returnValue($modifierIdentifier));
        $modifier->expects($this->any())
            ->method('getType')
            ->will($this->returnValue('deductible'));

        $this->transaction->addModifier($modifier);

        $this->assertTrue($this->transaction->getModifier($modifierIdentifier->getFull()) instanceof TransactionModifierInterface);

        $this->assertContainsOnlyInstancesOf(
            'Heystack\Ecommerce\Transaction\Interfaces\TransactionModifierInterface',
            $this->transaction->getModifiers()
        );

        $this->assertContainsOnlyInstancesOf(
            'Heystack\Ecommerce\Transaction\Interfaces\TransactionModifierInterface',
            $this->transaction->getModifiersByType('deductible')
        );
    }

    /**
     * @covers Heystack\Ecommerce\Transaction\Transaction::__construct
     * @covers Heystack\Ecommerce\Transaction\Transaction::addModifier
     * @covers Heystack\Ecommerce\Transaction\Transaction::getTotal
     * @covers Heystack\Ecommerce\Transaction\Transaction::saveState
     * @covers Heystack\Ecommerce\Transaction\Transaction::isValidStatus
     * @covers Heystack\Ecommerce\Transaction\Transaction::getTotalWithExclusions
     * @covers Heystack\Ecommerce\Transaction\Transaction::updateTotal
     */
    public function testGetTotal()
    {
        $this->assertEquals(new NZD(0), $this->transaction->getTotal());

        $this->transaction->updateTotal();

        $this->assertEquals(new NZD(100), $this->transaction->getTotal());

    }

    /**
     * @covers Heystack\Ecommerce\Transaction\Transaction::__construct
     * @covers Heystack\Ecommerce\Transaction\Transaction::addModifier
     * @covers Heystack\Ecommerce\Transaction\Transaction::isValidStatus
     * @covers Heystack\Ecommerce\Transaction\Transaction::getTotalWithExclusions
     * @covers Heystack\Ecommerce\Exception\MoneyOverflowException::__construct
     * @expectedException Heystack\Ecommerce\Exception\MoneyOverflowException
     */
    public function testGetTotalWithExclusions()
    {
        $modifierIdentifier = $this->mockIdentifier('voucher');

        $modifier = $this->getMock('Heystack\Ecommerce\Transaction\Interfaces\TransactionModifierInterface');
        $modifier->expects($this->any())
            ->method('getIdentifier')
            ->will($this->returnValue($modifierIdentifier));
        $modifier->expects($this->any())
            ->method('getType')
            ->will($this->returnValue('deductible'));
        $modifier->expects($this->any())
            ->method('getTotal')
            ->will($this->returnValue(new NZD(50)));

        $this->assertEquals(new NZD(100), $this->transaction->getTotalWithExclusions([]));

        $this->transaction->addModifier($modifier);

        $this->assertEquals(new NZD(50), $this->transaction->getTotalWithExclusions([]));

        $this->assertEquals(new NZD(100), $this->transaction->getTotalWithExclusions(['voucher']));

        $this->assertEquals(new NZD(-50), $this->transaction->getTotalWithExclusions(['purchasableholder']));

        $modifierIdentifier = $this->mockIdentifier('taxhandler');

        $modifier = $this->getMock('Heystack\Ecommerce\Transaction\Interfaces\TransactionModifierInterface');
        $modifier->expects($this->any())
            ->method('getIdentifier')
            ->will($this->returnValue($modifierIdentifier));
        $modifier->expects($this->any())
            ->method('getType')
            ->will($this->returnValue('chargeable'));
        $modifier->expects($this->any())
            ->method('getTotal')
            ->will($this->returnValue(new NZD(intval(PHP_INT_MAX - 49))));

        $this->transaction->addModifier($modifier);

        $this->transaction->getTotalWithExclusions([]);
    }

    /**
     * @covers Heystack\Ecommerce\Transaction\Transaction::__construct
     * @covers Heystack\Ecommerce\Transaction\Transaction::addModifier
     * @covers Heystack\Ecommerce\Transaction\Transaction::isValidStatus
     * @covers Heystack\Ecommerce\Transaction\Transaction::getStorableIdentifier
     */
    public function testGetStorableIdentifier()
    {
        $this->assertEquals(Transaction::IDENTIFIER, $this->transaction->getStorableIdentifier());
    }

    /**
     * @covers Heystack\Ecommerce\Transaction\Transaction::__construct
     * @covers Heystack\Ecommerce\Transaction\Transaction::addModifier
     * @covers Heystack\Ecommerce\Transaction\Transaction::isValidStatus
     * @covers Heystack\Ecommerce\Transaction\Transaction::getSchemaName
     */
    public function testGetSchemaName()
    {
        $this->assertEquals('Transaction', $this->transaction->getSchemaName());
    }

    /**
     * @covers Heystack\Ecommerce\Transaction\Transaction::__construct
     * @covers Heystack\Ecommerce\Transaction\Transaction::addModifier
     * @covers Heystack\Ecommerce\Transaction\Transaction::saveState
     * @covers Heystack\Ecommerce\Transaction\Transaction::updateTotal
     * @covers Heystack\Ecommerce\Transaction\Transaction::getTotalWithExclusions
     * @covers Heystack\Ecommerce\Transaction\Transaction::isValidStatus
     * @covers Heystack\Ecommerce\Transaction\Transaction::getStorableData
     */
    public function testGetStorableData()
    {
        $storableData = $this->transaction->getStorableData();

        $this->assertCount(3, $storableData);

        $this->assertContains('Transaction', $storableData);
        $this->assertContains('NZD', $storableData['flat']);
        $this->assertContains('Pending', $storableData['flat']);

        $this->assertEquals(0, $storableData['flat']['Total']);

        $this->transaction->updateTotal();
        $storableData = $this->transaction->getStorableData();
        $this->assertEquals(1, $storableData['flat']['Total']);
    }

    /**
     * @covers Heystack\Ecommerce\Transaction\Transaction::__construct
     * @covers Heystack\Ecommerce\Transaction\Transaction::addModifier
     * @covers Heystack\Ecommerce\Transaction\Transaction::isValidStatus
     * @covers Heystack\Ecommerce\Transaction\Transaction::getStorableBackendIdentifiers
     */
    public function testGetStorableBackendIdentifiers()
    {
        $this->assertEquals([Backend::IDENTIFIER], $this->transaction->getStorableBackendIdentifiers());
    }

    /**
     * @covers Heystack\Ecommerce\Transaction\Transaction::__construct
     * @covers Heystack\Ecommerce\Transaction\Transaction::addModifier
     * @covers Heystack\Ecommerce\Transaction\Transaction::saveState
     * @covers Heystack\Ecommerce\Transaction\Transaction::setStatus
     * @covers Heystack\Ecommerce\Transaction\Transaction::getStatus
     * @covers Heystack\Ecommerce\Transaction\Transaction::isValidStatus
     * @expectedException \InvalidArgumentException
     */
    public function testStatus()
    {
        $this->transaction->setStatus('Pending');
        $this->assertEquals('Pending', $this->transaction->getStatus());

        $this->transaction->setStatus('invalid_status');
    }
}
