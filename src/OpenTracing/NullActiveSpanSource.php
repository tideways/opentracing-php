<?php

namespace OpenTracing;

class NullActiveSpanSource implements ActiveSpanSource
{
    private $activeSpan;

    public function __construct()
    {
        $this->activeSpan = new NullSpan();
    }

    /**
     * Sets the given `Span` as active, so that it is used as a parent when creating new spans.
     *
     * The implementation must keep track of the active spans sequence, so
     * that previous spans can be resumed after a deactivation.
     */
    public function makeActive(Span $span)
    {
    }

    /**
     * Returns the `Span` that is currently activated for this source.
     *
     * @return \OpenTracing\Span
     */
    public function getActiveSpan()
    {
        return $this->activeSpan;
    }

    /**
     * Deactivate the given `Span`, restoring the previous active one.
     *
     * This method must take in consideration that a `Span` may be deactivated
     * when it's not really active. In that case, the current active stack
     * must not be changed.
     */
    public function deactivate(Span $span)
    {
    }
}
