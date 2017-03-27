<?php

namespace OpenTracing;

interface Tracer
{
    public function startSpan($operationName, array $options) : Span;

    public function inject(SpanContext $context, $format, $carrier);

    public function extract($format, $carrier) : SpanContext;

    /**
     * Allow tracer to transmit span data out of process.
     *
     * This method may not be needed depending on the tracer implementation,
     * but every user should make sure to call it at the end of the PHP
     * request.  In PHP-FPM based applictions it makes sense to wait after the
     * call to {@see fastcgi_finish_request} to avoid blocking the user
     * request.
     *
     * OpenTracing does not contain this method in other APIs, because they
     * usually can move spans to other threads whenever a Span is finished at
     * very low cost before sending them out of process.
     */
    public function flush();
}
