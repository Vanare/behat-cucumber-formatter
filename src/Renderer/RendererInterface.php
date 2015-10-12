<?php

namespace behatJunitFormatter\Renderer;

interface RendererInterface
{
    public function render();

    public function getResult();
}
