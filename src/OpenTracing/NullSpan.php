<?php

namespace OpenTracing;

class NullSpan implements Span
{
    private $context;

    public function __construct()
    {
        $this->context = new NullSpanContext();
    }

    public function getContext()
    {
        return $this->context;
    }

    public function setTag($name, $value)
    {
        return $this;
    }

    public function log($event, array $fields = [], $timestamp = null)
    {
        return $this;
    }

    public function finish($timestamp = null)
    {
    }

    public function setBaggageItem($name, $value)
    {
    }

    public function getBaggageItem($name)
    {
    }
}
