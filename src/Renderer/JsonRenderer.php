<?php

namespace App\Renderer;

use App\Formatter\FormatterInterface;

class JsonRenderer implements RendererInterface
{
    /**
     * @var FormatterInterface
     */
    protected $formatter;

    /**
     * @var string
     */
    protected $result;

    /**
     * @param FormatterInterface $formatter
     */
    public function __construct(FormatterInterface $formatter)
    {
        $this->formatter = $formatter;
    }

    public function render()
    {
        // @TODO Render structure like https://www.relishapp.com/cucumber/cucumber/docs/formatters/json-output-formatter
    }

    /**
     * @return string
     */
    public function getResult()
    {
        return $this->result;
    }
}
