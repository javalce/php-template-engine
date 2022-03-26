<?php

namespace App\Template;

use InvalidArgumentException;
use LogicException;

/**
 * Template class
 *
 * Blocks in the template can be accessed by using block method.
 * Allows access to shared environment variables as class variables with magic get and set.
 */
final class Template
{
    protected $templatePath;
    /**
     * The template environment
     *
     * @var \App\Template\Environment
     */
    protected $environment = null;

    /**
     * The template content
     *
     * @var string
     */
    protected $content;

    /**
     * The block stack
     *
     * @var array<\App\Template\Block>
     */
    private $stack = array();

    /**
     * The blocks in template
     *
     * @var array<\App\Template\Block>
     */
    protected $blocks = array();

    /**
     * The template that extends this template
     *
     * @var \App\Template\Template
     */
    protected $extends = null;

    /**
     * The variables in this template
     *
     * @var array
     */
    private $variables;

    /**
     * Constructs a template from a file path and the template variables.
     * If file path is null, constructs an empty template
     *
     * @param string $path the file path
     * @param array $variables
     */
    public function __construct($path = null, $variables = array())
    {
        $this->templatePath = $path;
        $this->content = new Block();
        $this->variables = $variables;
    }

    /**
     * Creates a template within an environment
     *
     * @param Environment $environment
     * @param string|null $path
     * @param array $variables
     * @return \App\Template\Template
     */
    public static function withEnvironment($environment, $path, $variables = array())
    {
        if ($path === null) {
            $obj = new self(null, $variables);
        } else {
            $obj = new self($environment->getTemplatePath($path), $variables);
        }
        $obj->environment = $environment;
        return $obj;
    }

    /**
     * Allows this template to extend another template.
     * A template can only extend one other template at a time however
     * you can extend a template extending another template etc.
     *
     * @param string|null $path
     */
    public function extend($path)
    {
        if ($this->environment !== null && $this->templatePath != $this->environment->getTemplatePath($path)) {
            $this->extends = Template::withEnvironment($this->environment, $path, $this->variables);
        } else if ($this->templatePath != $path) {
            $this->extends = new Template($path, $this->variables);
        }
    }

    /**
     * Gets a block
     *
     * @param string $name
     * @return \App\Template\Block
     * @throws LogicException
     */
    public function block($name)
    {
        if (isset($this->blocks[$name])) {
            return $this->blocks[$name];
        }

        throw new LogicException(sprintf("The block %s does not exist", $name));
    }

    /**
     * Indicates the start of a block.
     *
     * @param string $name name of the block
     * @param string $value optional value for the block
     * @throws LogicException
     */
    public function start($name)
    {
        if (empty($name)) {
            throw new LogicException(sprintf("You cannot create a block with %s as a name!", $name));
        }

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

    /**
     * Indicates the end of a block.
     *
     * @return void
     */
    public function end()
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

    /**
     * Gets the blocks.
     *
     * @return array<\App\Template\Block>
     */
    public function getBlocks()
    {
        return $this->blocks;
    }

    /**
     * Sets the blocks.
     *
     * @param array<\App\Template\Block> $blocks
     */
    public function setBlocks(array $blocks)
    {
        $this->blocks = $blocks;
    }

    /**
     * Renders a template and returns it as a string.
     *
     * @param array $variables
     * @return string
     * @throws InvalidArgumentException
     */
    public function render($variables = null)
    {
        if ($this->templatePath !== null) {
            $_file = $this->templatePath;
            if (!file_exists($_file)) {
                throw new InvalidArgumentException(sprintf('Could not render. The file %s could not be found', $_file));
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

    /**
     * Sets template environment
     *
     * @param Environment $environment
     */
    public function setEnvironment(Environment $environment)
    {
        $this->environment = $environment;
    }

    /**
     * Magic isset
     *
     * @param string $name
     * @return boolean
     */
    public function __isset($name)
    {
        return isset($this->environment->$name);
    }

    /**
     * Magic getter
     *
     * @param string $name
     * @return string
     */
    public function __get($name)
    {
        return $this->environment->$name;
    }

    /**
     * Magic setter
     *
     * @param string $name
     * @param mixed $value
     */
    public function __set($name, $value)
    {
        $this->environment->$name = $value;
    }
}