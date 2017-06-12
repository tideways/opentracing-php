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
            'childOf' => $context,
            'tags' => array('foo' => 'bar'),
            'startTime' => $date = new DateTime(),
        ));

        $this->assertSame($context, $options->getChildOf());
        $this->assertEquals(array('foo' => 'bar'), $options->getTags());
        $this->assertSame($date, $options->getStartTime());

        $options = new SpanOptions(array(
            'child_of' => $context,
            'start_time' => $date = new DateTime(),
        ));

        $this->assertSame($context, $options->getChildOf());
        $this->assertSame($date, $options->getStartTime());
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

        new SpanOptions(array('childOf' => $context, 'references' => [$context]));
    }
}
