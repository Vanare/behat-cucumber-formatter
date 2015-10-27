<?php

namespace fourxxi\BehatCucumberJsonFormatter\Node;

class ExampleRow
{
    /**
     * @var array
     */
    private $cells = [];

    /**
     * @var int
     */
    private $line = 0;

    /**
     * @var string
     */
    private $id = '';

    /**
     * @return array
     */
    public function getCells()
    {
        return $this->cells;
    }

    /**
     * @param array $cells
     */
    public function setCells($cells)
    {
        $this->cells = $cells;
    }

    /**
     * @return int
     */
    public function getLine()
    {
        return $this->line;
    }

    /**
     * @param int $line
     */
    public function setLine($line)
    {
        $this->line = $line;
    }

    /**
     * @return string
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param string $id
     */
    public function setId($id)
    {
        $this->id = $id;
    }
}
