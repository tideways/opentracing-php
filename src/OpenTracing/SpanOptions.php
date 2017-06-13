<?php

namespace OpenTracing;

class SpanOptions
{
    /**
     * @var \OpenTracing\Span|OpenTracing\SpanContext
     */
    private $childOf;

    /**
     * @var \OpenTracing\Reference[]
     */
    private $references = array();

    /**
     * @var array<string,mixed>
     */
    private $tags = array();

    /**
     * @var float
     */
    private $startTime;

    public function __construct(array $options)
    {
        foreach ($options as $key => $value) {
            switch ($key) {
                case 'childOf':
                case 'child_of':
                    $this->assertSpanOrContext($value, $key);
                    $this->childOf = $value;
                    break;

                case 'references':
                    $this->assertTraversable($value, $key);

                    foreach ($value as $idx => $reference) {
                        if (!($reference instanceof Reference)) {
                            throw new \InvalidArgumentException(sprintf(
                                'Property "%s" must be a \OpenTracing\Reference, is: %s',
                                $key,
                                is_object($reference) ? get_class($reference) : gettype($reference)
                            ));
                        }

                        $this->references[] = $reference;
                    }
                    break;

                case 'tags':
                    $this->assertTraversable($value, $key);

                    foreach ($value as $tag => $tagValue) {
                        $this->tags[$tag] = $tagValue;
                    }
                    break;

                case 'startTime':
                case 'start_time':
                    if (!is_int($value) && !is_float($value)) {
                        throw new \InvalidArgumentException(sprintf(
                            'Property "%s" must be float|int, is: %s',
                            $key,
                            is_object($value) ? get_class($value) : gettype($value)
                        ));
                    }
                    $this->startTime = $value;
                    break;

                default:
                    throw new \InvalidArgumentException(sprintf(
                        'Property "%s" is not a valid span option.',
                        $key
                    ));
            }
        }

        if (count($this->references) && $this->childOf !== null) {
            throw new \InvalidArgumentException('You can only specify either "childOf" or "references", not both.');
        }
    }

    private function assertSpanOrContext($value, $property)
    {
        if (!($value instanceof Span) && !($value instanceof SpanContext)) {
            throw new \InvalidArgumentException(sprintf(
                'Property "%s" must be a \OpenTracing\Span or \OpenTracing\SpanContext, is: %s',
                $property,
                is_object($value) ? get_class($value) : gettype($value)
            ));
        }
    }

    private function assertTraversable($value, $property)
    {
        if (!is_array($value) && !($value instanceof \Traversable)) {
            throw new \InvalidArgumentException(sprintf(
                'Property "%s" must be an array or Traversable/Iterator, is: %s',
                $property,
                is_object($value) ? get_class($value) : gettype($value)
            ));
        }
    }

    public function getChildOf()
    {
        return $this->childOf;
    }

    public function getReferences()
    {
        return $this->references;
    }

    public function getTags()
    {
        return $this->tags;
    }

    public function getStarTtime()
    {
        return $this->startTime;
    }
}
