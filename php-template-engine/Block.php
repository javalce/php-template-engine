<?php

namespace SimpleTemplateEngine;

use Closure;
use Exception;

final class Block
{
    public $name;
    public $content = '';
    public $escaped = false;

    public function __construct($name = null)
    {
        $this->name = $name;
    }

    public function append($content)
    {
        $this->content .= $content;
    }

    public function prepend($content)
    {
        $this->content = $content . $this->content;
    }

    public function escape()
    {
        if (!$this->escaped) {
            return htmlspecialchars($this->content, ENT_QUOTES);
        }

        return $this->content;
    }

    public function e()
    {
        return $this->escape();
    }

    public function call($function)
    {
        if ($function instanceof Closure || is_string($function) && function_exists($function)) {
            return $function($this->content);
        }

        throw new Exception(sprintf('The function %s cannot be called', $function));
    }

    public function __toString()
    {
        return $this->content;
    }
}