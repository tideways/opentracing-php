# OpenTracing PHP

This package is a PHP platform API for OpenTracing. The API is modelled very
closely to both opentracing-python and opentracing-ruby to be simple, easy to
use and honor the languages core functionality.

## Required reading

In order to understand the PHP platform API, one must first be familiar with the
[OpenTracing project](http://opentracing.io) and
[terminology](http://opentracing.io/documentation/pages/spec.html) more specifically.

## Installation

OpenTracing-PHP can be installed via Composer:

```sh
composer require opentracing/opentracing-php
```

## Usage

### Static registry initialization

For ease of use within any kind of application (with or without service container, factory or registry)
the OpenTracing library provides a static registry, where you should push your tracer to. The assumption
is that you are only using a single tracer in your infrastructure or could use a composite pattern
if you use multiple ones.

```php
<?php

use OpenTracing;

OpenTracing::setGlobalTracer(new MyTracerImplementation());
```

This library only provides the interfaces for implementing your own tracer, no tracer is included.

To implement your own Tracer look at the `OpenTracing\Tracer`, `OpenTracing\Span` and `OpenTracing\SpanContext` interfaces.

### Non-static usage

You can manage the tracer instance lifecycle yourself and start spans from it.
When you combine OpenTracing with the [PSR-11 Container](https://github.com/php-fig/fig-standards/blob/master/accepted/PSR-11-container.md),
you should expect a tracer service to be named `OpenTracing.Tracer`.

### Flushing Spans

PHP as a request scoped language has no simple means to pass of collected span
data to a background process without blocking the main request thread/process.
The OpenTracing API makes no assumptions about this, but for PHP that might
cause problems for Tracer implementations. This is why the PHP API contains a
`flush` method that allows to trigger a span sending out of process.

```php
<?php

use OpenTracing;

// Do application work, buffer spans in memory
$application->run();

fastcgi_finish_request();

$tracer = OpenTracing::getGlobalTracer();
$tracer->flush(); // release buffer to backend
```

This is optional, tracers can decide to immediately send finished spans to a
backend. The flush call can be implemented as a NO-OP for these tracers.

### Starting an empty trace by creating a "root span"

Its always possible to create a "root" span with no parent or causal reference:

```php
<?php

use OpenTracing;

$span = OpenTracing::startManualSpan("operation_name");
$span->finish();

$tracer = OpenTracing::getGlobalTracer();
$span = $tracer->startManualSpan("operation_name");
$span->finish();
```

### Creating a child Span given an existing parent Span

```php
<?php

use OpenTracing;

$parent = OpenTracing::startManualSpan('parent');
$child = OpenTracing::startManualSpan('child', ['child_of' => $parent]);
$child->finish();
$parent->finish();

$tracer = OpenTracing::getGlobalTracer();
$parent = $tracer->startManualSpan('parent');
$child = $tracer->startManualSpan('child', ['child_of' => $parent]);
$child->finish();
$parent->finish();
```

### Creating a child Span using automatic active span management

```php
<?php

use OpenTracing;

$parent = OpenTracing::startActiveSpan('parent');
$child = OpenTracing::startActiveSpan('child');
$child->finish();
$parent->finish();
```

### Serializing to the wire

When you make a call to a downstream service, via HTTP using cURL or
file_get_contents  then you need to propagate the trace context via HTTP
headers, and serialize them to the wire.

```php
<?php

use OpenTracing;

$headers = [];

$span = OpenTracing::startManualSpan("my_span");
OpenTracing::inject($span->getContext(), OpenTracing::FORMAT_HTTP_HEADERS, $headers);

$ch = curl_init("http://opentracing.io");
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
/** format: ['Trace-Id: 1234'] */
curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
curl_exec($ch);

$span->finish();
```

### Deserializing from the wire (From Globals)

PHP always has HTTP Headers in ``$_SERVER``. If your application(s) use http
header for context propagation, then the simplest way to join an existing trace
is to extract the data directly from the server globals.

```php
<?php

use OpenTracing;

$context = OpenTracing::extract(OpenTracing::FORMAT_SERVER_GLOBALS, $_SERVER);

$span = OpenTracing::startManualSpan("my_span", ["child_of" => $context]);
```

### Working with multiple References

If you don't have a simple parent child relationship and `child_of` is not enough,
then you can use the `OpenTracing\Reference` object and `references` option to
specify the relationships:

```php
<?php

use OpenTracing;
use OpenTracing\Reference;

$parent1 = OpenTracing::startManualSpan('parent');
$parent2 = OpenTracing::startManualSpan('parent');

$child = \OpenTracing::startManualSpan('child', [Reference::followsFrom($parent1), Reference::followsFrom($parent2)]);
$child->finish();
$parent->finish();
```

### Using Span Options

Passing options to the pass can be done using either an array or the
SpanOptions wrapper object. The following keys are valid:

- `start_time` is a float (or int) representing a timestamp with arbitrary precision.
- `child_of` is an object of type `OpenTracing\SpanContext` or `OpenTracing\Span`.
- `references` is an array of `OpenTracing\Reference` objects
- `tags` is an array with string keys and mixed values that represent OpenTracing tags.

If you want more type safety you can use the SpanOptions directly, but
technically only the Tracer implementation needs them to validate the inputs.

```php
<?php

use OpenTracing\SpanOptions;

$span = $tracer->startManualSpan('operation', new SpanOptions([
    'child_of' => $parentContext,
    'tags' => ['foo' => 'bar'],
    'start_time' => $microtime,
]));
```

### Propagation Formats

The propagation formats should be implemented consistently across all tracers.
If you want to implement your own format, then don't reuse the existing constants.
Tracers will throw an exception if the requested format is not handled by them.

- `FORMAT_TEXT_MAP` should represens the span context as a key value map. There is no
  assumption about the semantics where the context is coming from and sent to.

- `FORMAT_HTTP_HEADERS` should represent the span context as HTTP header lines
  in an array list. For two context details "Span-Id" and "Trace-Id", the
  result would be `['Span-Id: 1234', 'Trace-Id: 4567']`. This definition can be
  passed directly to curl and file_get_contents.

- `FORMAT_SERVER_GLOBALS` should represent the span context as key value HTTP
  header pairs as used in the PHP global `S_SERVER`, with uppercase keys and a
  prefix `HTTP_`.

- `FORMAT_BINARY` makes no assumptions about the data format other than it is
  proprioratry and each Tracer can handle it as it wants.
