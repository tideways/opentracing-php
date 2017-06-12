<?php

namespace OpenTracing;

use InvalidArgumentException;

class ReferenceTest extends \PHPUnit_Framework_TestCase
{
    public function testInvalidChildOf()
    {
        $this->setExpectedException(InvalidArgumentException::class);

        Reference::childOf(null);
    }

    public function testInvalidFollowsFrom()
    {
        $this->setExpectedException(InvalidArgumentException::class);

        Reference::followsFrom(null);
    }

    public function testChildOf()
    {
        $context = $this->getMock(SpanContext::class);

        $reference = Reference::childOf($context);
        $this->assertSame($context, $reference->getContext());
        $this->assertTrue($reference->isChildOf());
    }

    public function testFollowsFrom()
    {
        $context = $this->getMock(SpanContext::class);

        $reference = Reference::followsFrom($context);
        $this->assertSame($context, $reference->getContext());
        $this->assertTrue($reference->isFollowsFrom());
    }
}
