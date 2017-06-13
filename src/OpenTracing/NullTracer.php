<?php

namespace OpenTracing;

class NullTracer implements Tracer
{
    public function startSpan($operationName, array $options)
    {
        return new NullSpan();
    }

    public function inject(SpanContext $context, $format, &$carrier)
    {
    }

    public function extract($format, $carrier)
    {
        return new NullSpanContext();
    }
}
