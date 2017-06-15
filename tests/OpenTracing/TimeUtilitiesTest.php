<?php

namespace OpenTracing;

class TimeUtilitiesTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @dataProvider dataValidMicrotimes
     */
    public function testCreateFromValidMicrotimes($expected, $microtime)
    {
        $date = TimeUtilities::createFromMicrotime($microtime);
        $this->assertEquals($expected, $date->format('Y-m-d H:i:s.u'));
    }

    public function dataValidMicrotimes()
    {
        return [
            ['2009-02-13 23:31:30.123500', 1234567890.123456],
            ['2019-01-19 09:28:32.222200', 1547890112.222222],
        ];
    }
}
