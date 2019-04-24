<?php

namespace Vanare\BehatCucumberJsonFormatter\Exception;

interface EnrichedExceptionInterface
{
    /**
     * @return null|string
     */
    public function getExtraMessage();
}