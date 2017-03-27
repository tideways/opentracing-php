<?php

class OpenTracingTest extends \PHPUnit_Framework_TestCase
{
    public function testStartSpanFromGlobalTracer()
    {
        $tracer = $this->getMockBuilder('OpenTracing\Tracer')->getMock();
        $tracer->method('startSpan')->willReturn($this->getMockBuilder('OpenTracing\Span')->getMock());

        \OpenTracing::setGlobalTracer($tracer);

        $span = \OpenTracing::startSpan('php');

        $this->assertInstanceOf('OpenTracing\Span', $span);
    }
}
