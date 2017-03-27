<?php

namespace OpenTracing;

use DateTime;

interface Span
{
    public function getContext();

    public function setTag($name, $value);

    public function log($event, array $fields = [], DateTime $timestamp = null);

    public function finish(DateTime $timestamp = null);

    public function setBaggageItem($name, $value);

    public function getBaggageItem($name);
}
