<?php

// @TODO remove BaseSuite

namespace App\Node;

use emuse\BehatHTMLFormatter\Classes\Suite as BaseSuite;

class Suite extends BaseSuite
{
    /**
     * @return Feature[]
     */
    public function getFeatures()
    {
        return parent::getFeatures();
    }
}
