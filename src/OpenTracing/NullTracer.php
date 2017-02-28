<?php

namespace OpenTracing;

class NullTracer implements Tracer
{
    public function startSpan($operationName, array $options) : Span
    {
        return new NullSpan();
    }

    public function inject(SpanContext $context, $format, $carrier)
    {
        return $carrier;
    }

    public function extract($format, $carrier) : SpanContext
    {
        return new NullSpanContext();
    }
}
