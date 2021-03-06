<?php

namespace OpenTracing;

use DateTime;
use InvalidArgumentException;

class SpanOptionsTest extends \PHPUnit_Framework_TestCase
{
    public function testCreateValidOptions()
    {
        $context = $this->getMock('OpenTracing\SpanContext');

        $options = new SpanOptions(array(
            'child_of' => $context,
            'tags' => array('foo' => 'bar'),
            'start_time' => $date = microtime(true),
        ));

        $this->assertSame($context, $options->getChildOf());
        $this->assertEquals(array('foo' => 'bar'), $options->getTags());
        $this->assertSame($date, $options->getStartTime());
    }

    public function testValidReferences()
    {
        $context = $this->getMock('OpenTracing\SpanContext');

        $options = new SpanOptions(array(
            'references' => [Reference::childOf($context)],
        ));
    }

    public function testCreateInvalidChildOf()
    {
        $this->setExpectedException(InvalidArgumentException::class);

        new SpanOptions(array('child_of' => null));
    }

    public function testCreateInvalidReferencesNotTraversable()
    {
        $this->setExpectedException(InvalidArgumentException::class);

        new SpanOptions(array('references' => null));
    }

    public function testCreateInvalidReferencesNotContext()
    {
        $this->setExpectedException(InvalidArgumentException::class);

        new SpanOptions(array('references' => [null]));
    }

    public function testCreateInvalidTags()
    {
        $this->setExpectedException(InvalidArgumentException::class);

        new SpanOptions(array('tags' => null));
    }

    public function testCreateInvalidChildOfAndReferences()
    {
        $this->setExpectedException(InvalidArgumentException::class);

        $context = $this->getMock('OpenTracing\SpanContext');

        new SpanOptions(array('child_of' => $context, 'references' => [$context]));
    }
}
