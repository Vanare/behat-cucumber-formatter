<?php

namespace vanare\BehatJunitFormatter\Renderer;

interface RendererInterface
{
    public function render();

    public function getResult();
}
