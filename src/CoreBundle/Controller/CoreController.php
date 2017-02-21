<?php

namespace Dwl\Exim\CoreBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class CoreController extends Controller
{
    public function indexAction($name)
    {
        return $this->render('DwlEximCoreBundle:Core:index.html.twig', array('name' => $name));
    }

}
