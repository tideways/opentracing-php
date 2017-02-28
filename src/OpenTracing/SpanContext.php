<?php

namespace OpenTracing;

interface SpanContext
{
    public function getBaggage() : array;
}
