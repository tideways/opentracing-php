<?php

namespace OpenTracing;

interface Tracer
{
    /**
     * @param string $operationName
     * @return \OpenTracing\Span
     */
    public function startSpan($operationName, array $options = array());

    /**
     * @param string $format
     * @param mixed $carrier
     */
    public function inject(SpanContext $context, $format, &$carrier);

    /**
     * @param string $format
     * @param mixed $carrier
     * @return \OpenTracing\SpanContext
     */
    public function extract($format, $carrier);

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
