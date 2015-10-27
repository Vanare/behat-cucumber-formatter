<?php

namespace fourxxi\BehatCucumberJsonFormatter\Node;

use fourxxi\BehatCucumberJsonFormatter\Node\ExampleRow;

class Example
{
    /**
     * @var string
     */
    private $id = '';

    /**
     * @var string
     */
    private $keyword = '';

    /**
     * @var string
     */
    private $name = '';

    /**
     * @var int
     */
    private $line = 0;

    /**
     * @var string
     */
    private $description = '';

    /**
     * @var ExampleRow[]
     */
    private $rows = [];

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

    /**
     * @return string
     */
    public function getKeyword()
    {
        return $this->keyword;
    }

    /**
     * @param string $keyword
     */
    public function setKeyword($keyword)
    {
        $this->keyword = $keyword;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param string $name
     */
    public function setName($name)
    {
        $this->name = $name;
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
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * @param string $description
     */
    public function setDescription($description)
    {
        $this->description = $description;
    }

    /**
     * @return ExampleRow[]
     */
    public function getRows()
    {
        return $this->rows;
    }

    /**
     * @param array $rows
     */
    public function setRows($rows)
    {
        $this->rows = $rows;
    }
}
