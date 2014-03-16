<?php

namespace Heystack\Ecommerce\Controller;

class InputControllerTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @covers \Heystack\Ecommerce\Controller\InputController::__construct
     */
    public function testCanConstructObjectWithValidArguments()
    {
        $this->assertTrue(
            is_object(
                $controller = new InputController(
                    $this->getMock('Heystack\Core\Input\Handler'),
                    $this->getMock('Heystack\Core\Output\Handler')
                )
            )
        );
        
        return $controller;
    }

    /**
     * @covers \Heystack\Ecommerce\Controller\InputController::getInputHandler
     * @depends testCanConstructObjectWithValidArguments
     */
    public function testCanGetInputHandler($c)
    {
        $this->assertEquals(
            $this->readAttribute($c, 'inputHandler'),
            $c->getInputHandler()
        );
    }

    /**
     * @covers \Heystack\Ecommerce\Controller\InputController::getOutputHandler
     * @depends testCanConstructObjectWithValidArguments
     */
    public function testCanGetOutputHandler($c)
    {
        $this->assertEquals(
            $this->readAttribute($c, 'outputHandler'),
            $c->getOutputHandler()
        );
    }

    /**
     * @covers \Heystack\Ecommerce\Controller\InputController::__construct
     */
    public function testProcessDoesProduceExpectedOutput()
    {
        $controller = new InputController(
            $inputMock = $this->getMock('Heystack\Core\Input\Handler'),
            $outptuMock = $this->getMock('Heystack\Core\Output\Handler')
        );
        
        $request = new \SS_HTTPRequest(
            'GET',
            '/input/process/test/'
        );

        $inputMock->expects($this->once())
            ->method('process')
            ->with('test', $request)
            ->will($this->returnValue(['success' => true]));
        
        $outptuMock->expects($this->once())
            ->method('process')
            ->with('test', $controller, ['success' => true])
            ->will($this->returnValue('yay'));

        $response = $controller->handleRequest(
            $request,
            \DataModel::inst()
        );
        
        $this->assertEquals(
            'yay',
            $response->getBody()
        );
    }
}