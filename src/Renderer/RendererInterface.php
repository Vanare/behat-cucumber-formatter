<?php

namespace Vanare\BehatCucumberJsonFormatter\Renderer;

interface RendererInterface
{
    public function render();

    public function getResult();
}
