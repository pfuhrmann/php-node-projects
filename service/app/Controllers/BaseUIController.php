<?php

namespace COMP1688\CW\Controllers;

use Twig_Environment;

abstract class BaseUIController {

    /**
     * @var Twig_Environment
     */
    protected $twig;

    /**
     * @param Twig_Environment $twig
     */
    public function __construct(Twig_Environment $twig)
    {
        $this->twig = $twig;
    }

    /**
     * Render Twig template
     *
     * @param string $template
     * @param array  $options
     */
    protected function render($template, array $options)
    {
        $template = $this->twig->loadTemplate($template.'.twig');
        echo $template->render($options);
    }
}
