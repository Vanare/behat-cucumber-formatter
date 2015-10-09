<?php

namespace App\Formatter;

use Behat\Testwork\Output\Formatter;
use App\Node\Suite;

interface FormatterInterface extends Formatter
{
    /**
     * @return Suite[]
     */
    public function getSuites();
}
