<?php

namespace vanare\Behat3CucumberJsonFormatter\Renderer;

interface RendererInterface
{
    public function render();

    public function getResult();
}
