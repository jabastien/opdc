<?php

require_once "DataCollector.php";
require_once "DataCollectorException.php";

/**
 * Test the DataCollector Class
 */
class DataCollectorTest extends PHPUnit_Framework_TestCase
{
    /**
     * @covers DataCollector::collectData
     */
    public function testCollectData()
    {
        $expected = 'EXPECTED DATA';

        $dataCollectorMock = $this->getMock('DataCollector', ['collectData']);
        $dataCollectorMock->expects($this->once())->method('collectData')->will($this->returnValue($expected));
        /** @var $dataCollectorMock DataCollector */
        $actual = $dataCollectorMock->collectData();
        $this->assertEquals($expected, $actual);
    }

    /**
     * @covers DataCollector::collectData
     * @expectedException DataCollectorException
     */
    public function testCollectDataUrlIsNull()
    {
        $expected = '{"devstatus": { "port": error}}';

        $dataCollector = new DataCollector();
        $actual = $dataCollector->collectData();
    }
}
