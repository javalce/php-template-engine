<?php

namespace SimpleTemplateEngine;

final class Environment
{
    public $templateDir;
    public $layout = null;
    public $variables = array();

    public function __construct($templateDir)
    {
        $this->templateDir = $templateDir;
    }

    public function render($path, array $variables = array())
    {
        $template = Template::withEnvironment($this, $path, $variables);
        return $template->render();
    }

    public function getTemplatePath($template)
    {
        return $this->templateDir . DIRECTORY_SEPARATOR . $template;
    }

    public function __isset($name)
    {
        return isset($this->variables[$name]);
    }

    public function __get($name)
    {
        return $this->variables[$name];
    }

    public function __set($name, $value)
    {
        $this->variables[$name] = $value;
    }
}