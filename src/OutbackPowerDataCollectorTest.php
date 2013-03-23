<?php

require_once "OutbackPowerDataCollector.php";
require_once "OutbackPowerDataCollectorException.php";

/**
 * Test the OutbackPowerDataCollector Class
 */
class OutbackPowerDataCollectorTest extends PHPUnit_Framework_TestCase
{
    /**
     * @covers OutbackPowerDataCollector::collectData
     */
    public function testCollectData()
    {
        $expected = '{"devstatus": { "port": error}}';

        $dataCollectorMock = $this->getMock('OutbackPowerDataCollector', ['collectData']);
        $dataCollectorMock->expects($this->once())->method('collectData')->will($this->returnValue($expected));
        /** @var $dataCollectorMock OutbackPowerDataCollector */
        $actual = $dataCollectorMock->collectData();
        $this->assertEquals($expected, $actual);
    }

    /**
     * @covers OutbackPowerDataCollector::collectData
     * @expectedException DataCollectorException
     */
    public function testCollectDataUrlIsNull()
    {
        $expected = '{"devstatus": { "port": error}}';

        $dataCollector = new OutbackPowerDataCollector();
        $actual = $dataCollector->collectData();
        $this->assertEquals($expected, $actual);
    }
}
