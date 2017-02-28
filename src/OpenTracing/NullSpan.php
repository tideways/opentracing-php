<?php

namespace OpenTracing;

class NullSpan implements Span
{
    public function getContext() : SpanContext
    {
        return NullSpanContext();
    }

    public function setTag(string $name, $value) : self
    {
        return $this;
    }

    public function log($event, array $fields = [], DateTime $timestamp = null) : self
    {
        return $this;
    }

    public function finish(DateTime $timestamp = null)
    {
    }

    public function setBaggageItem(string $name, string $value)
    {
    }

    public function getBaggageItem(string $name) : string
    {
    }
}
