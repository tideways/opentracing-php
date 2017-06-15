<?php

class OpenTracingTest extends \PHPUnit_Framework_TestCase
{
    public function testStartManualSpanFromGlobalTracer()
    {
        $tracer = $this->getMockBuilder('OpenTracing\Tracer')->getMock();
        $tracer->method('startManualSpan')->willReturn($this->getMockBuilder('OpenTracing\Span')->getMock());

        \OpenTracing::setGlobalTracer($tracer);

        $span = \OpenTracing::startManualSpan('php');

        $this->assertInstanceOf('OpenTracing\Span', $span);
    }

    public function testStartNullTracer()
    {
        $tracer = new \OpenTracing\NullTracer();

        $this->assertInstanceOf(\OpenTracing\ActiveSpanSource::class, $tracer->getActiveSpanSource());
        $this->assertInstanceOf(\OpenTracing\Span::class, $tracer->startManualSpan("foo"));
        $this->assertInstanceOf(\OpenTracing\Span::class, $tracer->startActiveSpan("foo"));
        $this->assertInstanceOf(\OpenTracing\Span::class, $tracer->getActiveSpan());
        $this->assertInstanceOf(\OpenTracing\SpanContext::class, $tracer->getActiveSpan()->getContext());
        $this->assertInstanceOf(\OpenTracing\SpanContext::class, $tracer->extract(\OpenTracing::FORMAT_TEXT_MAP, []));
    }
}
