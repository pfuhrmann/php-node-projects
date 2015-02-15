<?php

namespace COMP1688\CW\Controllers;

use Twig_Environment;

class TestController {

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
     * Display list of services
     *  GET /  HTTP/1.1
     * @returns string
     */
    public function getIndex()
    {
        return $this->render('index.html', []);
    }

    /**
     * Test Sitters service
     *  GET /test-sitters  HTTP/1.1
     * @returns string
     */
    public function getTestSitters()
    {
        return $this->render('tests/sitters.html', []);
    }

    /**
     * Test SittersDetails service
     *  GET /test-sitter-details  HTTP/1.1
     * @returns string
     */
    public function getTestSitterDetail()
    {
        return $this->render('tests/sitter_detail.html', []);
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