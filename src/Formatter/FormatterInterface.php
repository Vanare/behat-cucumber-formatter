<?php

namespace App\Formatter;

use Behat\Testwork\Output\Formatter as FormatterOutputInterface;
use App\Node\Suite;

interface FormatterInterface extends FormatterOutputInterface
{
    /**
     * @return Suite[]
     */
    public function getSuites();
}
