<?php

namespace OpenTracing;

use DateTimeImmutable;

class TimeUtilities
{
    /**
     * @return \DateTimeInterface
     */
    public function createFromMicrotime($microtime)
    {
        if (is_float($microtime)) {
            $format = 'U.u';
        } else if (is_int($microtime)) {
            $format = 'U';
        } else if (is_string($microtime)) {
            $format = 'U u';
        } else {
            throw new \InvalidArgumentException(
                'Given argument is not a known microtime format,
                which is either a float,
                string with format "sec msec" or unix timestamp integer for the full second.'
            );
        }

        $date = DateTimeImmutable::createFromFormat($format, $microtime);

        if (!$date) {
            throw new \InvalidArgumentException(
                'Given argument is not a known microtime format,
                which is either a float,
                string with format "sec msec" or unix timestamp integer for the full second.'
            );
        }

        return $date;
    }

    /**
     * @return \DateTimeInterface
     */
    public function now()
    {
        return self::createFromMicrotime(microtime(true));
    }
}
