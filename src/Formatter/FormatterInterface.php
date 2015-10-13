<?php

namespace behatJunitFormatter\Formatter;

use Behat\Testwork\Output\Formatter as FormatterOutputInterface;
use behatJunitFormatter\Node\Suite;

interface FormatterInterface extends FormatterOutputInterface
{
    /**
     * @return Suite[]
     */
    public function getSuites();
}
