<?php

use OpenTracing\Tracer;
use OpenTracing\NullTracer;
use OpenTracing\Span;
use OpenTracing\SpanContext;

final class OpenTracing
{
    const FORMAT_TEXT_MAP = 1;
    const FORMAT_BINARY = 2;
    const FORMAT_PSR7 = 3;
    const FORMAT_SYMFONY_HTTP = 4;

    const VERSION = "0.3.0";

    private static $tracer;

    public static function setGlobalTracer(Tracer $tracer)
    {
        self::$tracer = $tracer;
    }

    private static function getGlobalTracer() : Tracer
    {
        if (self::$tracer === null) {
            self::$tracer = new NullTracer();
        }

        return self::$tracer;
    }

    public static function startSpan(string $operationName, array $options) : Span
    {
        return self::getGlobalTracer()->startSpan($operationName, $options);
    }

    public static function inject(SpanContext $context, int $format, $carrier)
    {
        self::getGlobalTracer()->inject($context, $format, $carrier);
    }

    public static function extract(int $format, $carrier) : SpanContext
    {
        return self::getGlobalTracer()->extract($format, $carrier);
    }
}
