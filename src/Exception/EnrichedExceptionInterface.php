<?php

namespace Vanare\BehatCucumberJsonFormatter\Exception;

interface EnrichedExceptionInterface
{
    /**
     * @return mixed
     */
    public function getExtraData();
}