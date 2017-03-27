<?php

namespace OpenTracing;

interface SpanContext
{
    /**
     * @return array
     */
    public function getBaggage();
}
