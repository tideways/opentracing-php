<?php

namespace OpenTracing;

interface Tracer
{
    public function startSpan($operationName, array $options) : Span;

    public function inject(SpanContext $context, $format, $carrier);

    public function extract($format, $carrier) : SpanContext;
}
