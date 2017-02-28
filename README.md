# OpenTracing PHP

This package is a PHP platform API for OpenTracing.

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

```php
<?php

OpenTracing::setGlobalTracer(new MyTracerImplementation());
```

### Non-static usage

You can manage the tracer instance lifecycle yourself and start spans from it.
When you combine OpenTracing with the [PSR-11 Container](https://github.com/php-fig/fig-standards/blob/master/accepted/PSR-11-container.md),
you should expect a tracer service to be named `OpenTracing.Tracer`.

### Starting an empty trace by creating a "root span"

Its always possible to create a "root" span with no parent or casual reference:

```php
<?php

$span = \OpenTracing::startSpan("operation_name");
$span->finish();

$span = $tracer->startSpan("operation_name");
$span->finish();
```

### Creating a (child) Span given an existing (parent) Span

```
<?php

$parent = \OpenTracing::startSpan('parent');
$child = \OpenTracing::startSpan('child', ['child_of' => $parent]);
$child->finish();
$parent->finish();

$parent = $tracer->startSpan('parent');
$child = $tracer->startSpan('child', ['child_of' => $parent]);
$child->finish();
$parent->finish();
```

### Serializing to the wire

```
<?php

$request = new GuzzleHttp\Psr7\Request('GET', 'http://example.com');

$span = \OpenTracing::startSpan("my_span");
$request = \OpenTracing::inject($span->getContext(), OpenTracing::FORMAT_PSR7, $request);
$span->finish();
```

### Deserializing from the wire (From Globals)

```
<?php

$context = \OpenTracing::extract(\OpenTracing::FORMAT_TEXT_MAP, $_SERVER);

$span = \OpenTracing::startSpan("my_span", ["child_of" => $context]);
```
