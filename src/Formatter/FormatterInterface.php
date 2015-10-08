<?php

namespace App\Formatter;

use Behat\Testwork\Output\Formatter;
use emuse\BehatHTMLFormatter\Classes\Suite;

interface FormatterInterface extends Formatter
{
    /**
     * @return Suite[]
     */
    public function getSuites();
}
