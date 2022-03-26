<?php

namespace App\Template;

/**
 * Block class
 *
 * The Block class represents a block section in the template.
 */
final class Block
{
    /**
     * Name of this block
     *
     * @var string
     */
    public $name;

    /**
     * Content of this block
     *
     * @var string
     */
    public $content = '';

    /**
     * Controls if the content must be escaped
     *
     * @var boolean
     */
    public $escaped = false;

    /**
     * Constructor
     *
     * @param string $name
     */
    public function __construct($name = null)
    {
        $this->name = $name;
    }

    /**
     * Append to the content
     *
     * @param string $content
     * @return void
     */
    public function append($content)
    {
        $this->content .= $content;
    }

    /**
     * Prepend to the content
     *
     * @param string $content
     * @return void
     */
    public function prepend($content)
    {
        $this->content = $content . $this->content;
    }

    /**
     * Escapes the content and returns it.
     * If it's already escaped, it will simple return the content.
     *
     * @return string
     */
    public function escape()
    {
        if (!$this->escaped) {
            return htmlspecialchars($this->content, ENT_QUOTES);
        }

        return $this->content;
    }

    /**
     * Shorthand function for escape
     *
     * @return string
     */
    public function e()
    {
        return $this->escape();
    }

    /**
     * Returns the content.
     *
     * @return string
     */
    public function __toString()
    {
        return $this->content;
    }
}