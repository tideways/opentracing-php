<?php

namespace OpenTracing;

use DateTime;

interface Span
{
    public function getContext() : SpanContext;

    public function setTag(string $name, $value) : self;

    public function log($event, array $fields = [], DateTime $timestamp = null) : self;

    public function finish(DateTime $timestamp = null);

    public function setBaggageItem(string $name, string $value);

    public function getBaggageItem(string $name) : string;
}
