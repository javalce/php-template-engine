<?php

namespace App\Template;

/**
 * Environment class
 *
 * This is a factory class that creates Template objects.
 * Each Environment is associated with a directed of template files
 * from which the templates are loaded.
 *
 * The environment also holds shared variables amongst all Templates.
 * The variables can be accessed from any Template class created by this Environment.
 * This is useful for holding helpers such as routers, form helpers etc.
 */
final class Environment
{
    public $templateDir;
    public $layout = null;
    public $variables = array();

    /**
     * Constructor
     *
     * @param string $templateDir
     */
    public function __construct($templateDir = 'templates')
    {
        $this->templateDir = $templateDir;
    }

    /**
     * Render a template.
     *
     * @param string $path
     * @param array $variables
     * @return string
     * @throws \InvalidArgumentException
     */
    public function render($path, array $variables = array())
    {
        $template = Template::withEnvironment($this, $path, $variables);
        return $template->render();
    }

    /**
     * Gets the path of the template in this environment
     *
     * @param string $template
     * @return string
     */
    public function getTemplatePath($template)
    {
        $dir = dirname(__FILE__, 3);
        return $dir . DIRECTORY_SEPARATOR . $this->templateDir . DIRECTORY_SEPARATOR . $template;
    }

    /**
     * Magic isset
     *
     * @param string $name
     * @return boolean
     */
    public function __isset($name)
    {
        return isset($this->variables[$name]);
    }

    /**
     * Magic getter
     *
     * @param string $name
     * @return string
     */
    public function __get($name)
    {
        return $this->variables[$name];
    }

    /**
     * Magic setter
     *
     * @param string $name
     * @param mixed $value
     */
    public function __set($name, $value)
    {
        $this->variables[$name] = $value;
    }
}