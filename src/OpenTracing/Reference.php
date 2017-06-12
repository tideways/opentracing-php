<?php

namespace OpenTracing;

class Reference
{
    const CHILD_OF = 1;
    const FOLLOWS_FROM = 2;

    /**
     * @var \OpenTracing\SpanContext
     */
    private $context;

    private $relationship = self::CHILD_OF;

    static public function childOf($context)
    {
        return new self(self::extractContext($context), self::CHILD_OF);
    }

    static public function followsFrom($context)
    {
        return new self(self::extractContext($context), self::FOLLOWS_FROM);
    }

    static private function extractContext($context)
    {
        if ($context instanceof Span) {
            $context = $context->getContext();
        }

        if (!($context instanceof SpanContext)) {
            throw new \InvalidArgumentException(sprintf(
                'Reference requires \OpenTracing\Span or \OpenTracing\SpanContext as first argument, is: %s',
                is_object($context) ? get_class($context) : gettype($context)
            ));
        }

        return $context;
    }

    private function __construct(SpanContext $context, $relationship)
    {
        $this->context = $context;
        $this->relationship = $relationship;
    }

    public function getContext()
    {
        return $this->context;
    }

    public function isChildOf()
    {
        return $this->relationship === self::CHILD_OF;
    }

    public function isFollowsFrom()
    {
        return $this->relationship === self::FOLLOWS_FROM;
    }
}
