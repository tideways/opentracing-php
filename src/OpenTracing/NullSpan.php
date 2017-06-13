<?php

namespace OpenTracing;

class NullSpan implements Span
{
    public function getContext()
    {
        return NullSpanContext();
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
