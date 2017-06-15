<?php

namespace OpenTracing;

class NullTracer implements Tracer
{
    private $activeSpanSource;
    private $nullSpan;

    public function __construct()
    {
        $this->activeSpanSource = new NullActiveSpanSource();
        $this->nullSpan = new NullSpan();
    }

    public function getActiveSpanSource()
    {
        return $this->activeSpanSource;
    }

    public function getActiveSpan()
    {
        return $this->activeSpanSource->getActiveSpan();
    }

    public function startActiveSpan($operationName, $options = array())
    {
        return $this->nullSpan;
    }

    public function startManualSpan($operationName, $options = array())
    {
        return $this->nullSpan;
    }

    public function inject(SpanContext $context, $format, &$carrier)
    {
    }

    public function extract($format, $carrier)
    {
        return $this->nullSpan->getContext();
    }

    public function flush()
    {
    }
}
