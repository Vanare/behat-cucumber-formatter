<?php

namespace vanare\BehatJunitFormatter\Node;

use emuse\BehatHTMLFormatter\Classes\Suite as BaseSuite;
use vanare\BehatJunitFormatter\Node\Feature;

class Suite extends BaseSuite
{
    /**
     * @return Feature[]
     */
    public function getFeatures()
    {
        return BaseSuite::getFeatures();
    }
}
