<?php

namespace COMP1688\CW;

/**
 * Singleton used for recognition of environments
 *  based on hostnames
 */
class EnvironmentHelper {

    const DEV_NAME = 'comp1688-portal.app';
    const PROD_NAME = 'nginx.cms.gre.ac.uk';

    /**
     * @var EnvironmentHelper
     */
    private static $env;

    /**
     * @var string Server name
     */
    private $servName;

    private function __construct($servName) {
        $this->servName = $servName;
    }

    /**
     * Returns true if running locally
     * @return bool
     */
    public function isDev() {
        return ($this->servName === $this::DEV_NAME);
    }

    /**
     * Returns true if running on production
     * @return bool
     */
    public function isProd() {
        return ($this->servName === $this::PROD_NAME);
    }

    /**
     * @return string
     */
    public function getServName() {
        return $this->getServName();
    }

    /**
     * @return EnvironmentHelper
     */
    public static function getInstance()
    {
        if (!isset(self::$env)) {
            return self::$env = new EnvironmentHelper($_SERVER['SERVER_NAME']);
        }

        return self::$env;
    }
}
