<?php

require_once "IndexRange.php";

/**
 * Class IndexRangeTest
 */
class IndexRangeTest extends PHPUnit_Framework_TestCase {
    /**
     * @covers IndexRange::computeIndexRange
     * 
     * @dataProvider computeIndexRangeProvider
     */
    public function testComputeIndexRange($str, $expected)
    {
        $actual = IndexRange::computeIndexRange($str);
        $this->assertEquals($expected, $actual);
    }

    /**
     * data provider for testComputeIndexRange()
     *
     * @return array
     */
    public function computeIndexRangeProvider()
    {
        return [
            ['1', [1]],
            ['1,2', [1, 2]],
            ['2,1', [2, 1]],
            ['1,3', [1, 3]],
            ['1-3', [1, 2, 3]],
            ['3-1', [3, 2, 1]],
            ['1,2,4,6', [1, 2, 4, 6]],
            ['1,2,4-6', [1, 2, 4, 5, 6]],
            ['1,2,4-6,9', [1, 2, 4, 5, 6, 9]],
            ['1-6,9', [1, 2, 3, 4, 5, 6, 9]],
            ['10,3-1,4-6,9', [10, 3, 2, 1, 4, 5, 6, 9]],
            ['1,1,1', [1, 1, 1]],
            ['1-3,4-2', [1, 2, 3, 4, 3, 2]],
        ];
    }

    /**
     * @param array $haystack haystack
     * @param array $indexes  indexes
     * @param array $expected expected array
     *
     * @dataProvider arraySliceIndexedValuesProvider
     */
    public function testarraySliceIndexedValues(array $haystack, array $indexes, array $expected)
    {
        $actual = IndexRange::arraySliceIndexedValues($haystack, $indexes);
        $this->assertEquals($expected, $actual);
    }

    /**
     * data provider for testarraySliceIndexedValues
     *
     * @return array
     */
    public function arraySliceIndexedValuesProvider()
    {
        return [
            [['X', 'A', 'B'], [0, 1, 2], ['X', 'A', 'B']],
            [['X', 'A', 'B'], [1, 2], ['A', 'B']],
            [['X', 'A', 'B'], [2, 1], ['B', 'A']],
            [['X', 'A', 'B'], [1, 1], ['A', 'A']],
            [['X', 'A', 'B'], [1, 2, 2, 1], ['A', 'B', 'B', 'A']],
            [['X', 'A', 'B'], [], []],
        ];
    }
}
