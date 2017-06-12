<?php

namespace OpenTracing;

class SpanOptions
{
    /**
     * @var \OpenTracing\Span|OpenTracing\SpanContext
     */
    public $childOf;

    /**
     * @var \OpenTracing\Span[]|OpenTracing\SpanContext[]
     */
    public $references = array();

    /**
     * @var array<string,mixed>
     */
    public $tags = array();

    /**
     * @var \DateTimeInterface
     */
    public $startTime;

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
                        $this->assertSpanOrContext($reference, sprintf('%s[%s]', $key, $idx));

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
                    if (!($value instanceof \DateTimeInterface)) {
                        throw new \InvalidArgumentException(sprintf(
                            'Property "%s" must be \DateTimeInterface, is: %s',
                            $property,
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
        if (!$value instanceof Span && !$value instanceof SpanContext) {
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
}
