<?php

namespace OpenTracing;

interface Span
{
    /**
     * @return \OpenTracing\SpanContext
     */
    public function getContext();

    /**
     * @param string $name
     * @param mixed $value
     */
    public function setTag($name, $value);

    /**
     * @param string $event
     * @param float $timestamp
     */
    public function log($event, array $fields = [], $timestamp = null);

    /**
     * @param float $timestamp
     */
    public function finish($timestamp = null);

    /**
     * @param string $name
     * @param string $value
     */
    public function setBaggageItem($name, $value);

    /**
     * @param string $name
     * @return string
     */
    public function getBaggageItem($name);
}
