<?php

namespace App\Renderer;

interface RendererInterface
{
    public function render();

    public function getResult();
}
