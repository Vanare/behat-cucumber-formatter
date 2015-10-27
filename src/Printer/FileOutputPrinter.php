<?php

namespace fourxxi\BehatCucumberJsonFormatter\Printer;

use Behat\Testwork\Output\Exception\BadOutputPathException;
use Behat\Testwork\Output\Printer\OutputPrinter as OutputPrinterInterface;

class FileOutputPrinter implements OutputPrinterInterface
{
    /**
     * @var string
     */
    private $path;

    /**
     * @var string
     */
    private $filename;

    /**
     * @param $filename
     * @param $path
     */
    public function __construct($filename, $path)
    {
        $this->filename = $filename;
        $this->setOutputPath($path);
    }

    /**
     * Sets output path.
     *
     * @param string $path
     */
    public function setOutputPath($path)
    {
        if (!file_exists($path)) {
            if (!mkdir($path, 0755, true)) {
                throw new BadOutputPathException(
                    sprintf(
                        'Output path %s does not exist and could not be created!',
                        $path
                    ),
                    $path
                );
            }
        } else {
            if (!is_dir($path)) {
                throw new BadOutputPathException(
                    sprintf(
                        'The argument to `output` is expected to the a directory, but got %s!',
                        $path
                    ),
                    $path
                );
            }
        }
        $this->path = $path;
    }

    /**
     * Returns output path.
     *
     * @return null|string
     *
     * @deprecated since 3.1, to be removed in 4.0
     */
    public function getOutputPath()
    {
        return $this->path;
    }

    /**
     * Sets output styles.
     *
     * @param array $styles
     */
    public function setOutputStyles(array $styles)
    {
    }

    /**
     * Returns output styles.
     *
     * @return array
     *
     * @deprecated since 3.1, to be removed in 4.0
     */
    public function getOutputStyles()
    {
    }

    /**
     * Forces output to be decorated.
     *
     * @param Boolean $decorated
     */
    public function setOutputDecorated($decorated)
    {
    }

    /**
     * Returns output decoration status.
     *
     * @return null|Boolean
     *
     * @deprecated since 3.1, to be removed in 4.0
     */
    public function isOutputDecorated()
    {
    }

    /**
     * Sets output verbosity level.
     *
     * @param int $level
     */
    public function setOutputVerbosity($level)
    {
    }

    /**
     * Returns output verbosity level.
     *
     * @return int
     *
     * @deprecated since 3.1, to be removed in 4.0
     */
    public function getOutputVerbosity()
    {
        return 0;
    }

    /**
     * Writes message(s) to output stream.
     *
     * @param string|array $messages message or array of messages
     * @param bool         $append
     */
    public function write($messages, $append = false)
    {
        $file = $this->getOutputPath().DIRECTORY_SEPARATOR.$this->filename;

        if ($append) {
            file_put_contents($file, $messages, FILE_APPEND);
        } else {
            file_put_contents($file, $messages);
        }
    }

    /**
     * Writes newlined message(s) to output stream.
     *
     * @param string|array $messages message or array of messages
     */
    public function writeln($messages = '')
    {
        $this->write($messages, true);
    }

    /**
     * Clear output stream, so on next write formatter will need to init (create) it again.
     */
    public function flush()
    {
    }
}
