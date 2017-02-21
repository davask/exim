<?php

namespace Dwl\Exim\CoreBundle\Helper;

use Symfony\Component\DependencyInjection\ContainerInterface as Container;

class globalHelper {

    private $container;

    public function __construct(Container $container) {
        $this->container = $container;
    }

}
