<?php

namespace SimpleTemplateEngine;

use Exception;

final class Template
{
    protected $templatePath;
    /**
     * Undocumented variable
     *
     * @var Environment
     */
    protected $environment = null;
    protected $content;
    /**
     * Undocumented variable
     *
     * @var array<Block>
     */
    private $stack = array();
    /**
     * Undocumented variable
     *
     * @var array<Block>
     */
    protected $blocks = array();
    /**
     * Undocumented variable
     *
     * @var Template
     */
    protected $extends = null;

    private $variables;

    public function __construct($path = null, array $variables = array())
    {
        $this->templatePath = $path;
        $this->content = new Block();
        $this->variables = $variables;
    }

    public static function withEnvironment(Environment $environment, $path, array  $variables = array())
    {
        if ($path === null) {
            $obj = new self(null, $variables);
        } else {
            $obj = new self($environment->getTemplatePath($path), $variables);
        }
        $obj->environment = $environment;
        return $obj;
    }

    public function extend($path)
    {
        if ($this->environment !== null && $this->templatePath != $this->environment->getTemplatePath($path)) {
            $this->extends = Template::withEnvironment($this->environment, $path, $this->variables);
        } else if ($this->templatePath != $path) {
            $this->extends = new Template($path, $this->variables);
        }
    }

    public function getBlock($name)
    {
        if (isset($this->blocks[$name])) {
            return $this->blocks[$name];
        }
        return false;
    }

    public function block($name)
    {
        if (!empty($this->stack)) {
            $content = ob_get_contents();
            foreach ($this->stack as &$b) {
                $b->append($content);
            }
        }

        ob_start();
        $block = new Block($name);
        array_push($this->stack, $block);
    }

    public function endblock()
    {
        $content = ob_get_clean();

        foreach ($this->stack as &$b) {
            $b->append($content);
        }

        $block = array_pop($this->stack);

        if (($name = $block->name) != null) {
            $this->blocks[$name] = $block;
        }
    }

    public function getBlocks()
    {
        return $this->blocks;
    }

    public function setBlocks(array $blocks)
    {
        $this->blocks = $blocks;
    }

    public function render($variables = null)
    {
        if ($this->templatePath !== null) {
            $_file = $this->templatePath;
            if (!file_exists($_file)) {
                throw new Exception(sprintf('Could not render. The file %s could not be found', $_file));
            }

            $variables = $variables ?? $this->variables;

            extract($variables, EXTR_SKIP);
            ob_start();
            include $_file;
            $this->content->append(ob_get_clean());
        }

        if ($this->extends !== null) {
            $this->extends->setBlocks($this->getBlocks());
            $content = (string)$this->extends->render();
            return $content;
        }


        return (string)$this->content;
    }

    public function setEnvironment(Environment $environment)
    {
        $this->environment = $environment;
    }

    public function __isset($name)
    {
        return isset($this->environment->$name);
    }

    public function __get($name)
    {
        return $this->environment->$name;
    }

    public function __set($name, $value)
    {
        $this->environment->$name = $value;
    }
}