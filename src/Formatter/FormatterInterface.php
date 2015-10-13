<?php

namespace vanare\BehatJunitFormatter\Formatter;

use Behat\Testwork\Output\Formatter as FormatterOutputInterface;
use vanare\BehatJunitFormatter\Node\Suite;

interface FormatterInterface extends FormatterOutputInterface
{
    /**
     * @return Suite[]
     */
    public function getSuites();
}
