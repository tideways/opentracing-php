<?php

namespace OpenTracing;

class NullSpanContext implements SpanContext
{
    public function getBaggage()
    {
        return [];
    }
}
