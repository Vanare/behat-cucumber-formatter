<?php

/**
 * Created by PhpStorm.
 * User: nealv
 * Date: 05/01/15
 * Time: 12:50.
 */
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
