<?php

namespace OpenTracing;

use DateTime;

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
     */
    public function log($event, array $fields = [], DateTime $timestamp = null);

    public function finish(DateTime $timestamp = null);

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
