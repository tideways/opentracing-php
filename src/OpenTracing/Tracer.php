<?php

namespace OpenTracing;

interface Tracer
{
    /**
     * @return \OpenTracing\ActiveSpanSource
     */
    public function getActiveSpanSource();

    /**
     * @return \OpenTracing\Span
     */
    public function getActiveSpan();

    /**
     * Starts and returns a new `Span` representing a unit of work.
     *
     * This method differs from `startManualSpan` because it uses in-process
     * context propagation to keep track of the current active `Span` (if
     * available).
     *
     *	Starting a root `Span` with no casual references and a child `Span`
     *  in a different function, is possible without passing the parent
     *  reference around:
     *
     *      function handleRequest($request)
     *      {
     *          $rootSpan = $this->tracer->startActiveSpan('request.handler');
     *          $data = $this->getData($request);
     *      }
     *
     *      function getData($request)
     *      {
     *          // `$chilSpan` has `$rootSpan` as parent.
     *          $childSpan = $this->tracer->startActiveSpan('db.query');
     *      }
     *
     * @param string $operationName
     * @param array|SpanOptions $options
     * @return \OpenTracing\Span
     */
    public function startActiveSpan($operationName, $options = array());

    /**
     * Starts and returns a new Span representing a unit of work.
     *
     * @param string $operationName
     * @param array|SpanOptions $options
     * @return \OpenTracing\Span
     */
    public function startManualSpan($operationName, $options = array());

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
