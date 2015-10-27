<?php

namespace fourxxi\BehatCucumberJsonFormatter\Renderer;

interface RendererInterface
{
    public function render();

    public function getResult();
}
