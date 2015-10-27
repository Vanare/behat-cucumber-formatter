<?php

namespace fourxxi\BehatCucumberJsonFormatter\Formatter;

use Behat\Testwork\Output\Formatter as FormatterOutputInterface;
use fourxxi\BehatCucumberJsonFormatter\Node\Suite;

interface FormatterInterface extends FormatterOutputInterface
{
    /**
     * @return Suite[]
     */
    public function getSuites();
}
