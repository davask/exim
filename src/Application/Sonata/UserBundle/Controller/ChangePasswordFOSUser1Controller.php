<?php

/*
 * This file is part of the Sonata Project package.
 *
 * (c) Thomas Rabaix <thomas.rabaix@sonata-project.org>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Application\Sonata\UserBundle\Controller;

use FOS\UserBundle\Model\UserInterface;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\File\Exception\AccessDeniedException;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;

/**
 * Class ChangePasswordFOSUser1Controller.
 *
 * This class is inspired from the FOS Change Password Controller
 *
 *
 * @author  Hugo Briand <briand@ekino.com>
 */
class ChangePasswordFOSUser1Controller extends Controller
{
    private $doctrine;

    private $em;

    private $request;

    private $_format;

    private $translator;

    private function postConstruct() {

        $this->doctrine = $this->getDoctrine();
        $this->em = $this->doctrine->getEntityManager();
        $this->repo = $this->em->getRepository('ApplicationSonataUserBundle:User');

        $this->request = $this->getRequest();
        $this->_format = $this->request->attributes->get('_format', $this->getParameter('exim.theme.front.format'));

        $this->translator = $this->get('translator');

    }

    /**
     * @Rest\View
     */
    private function viewDatasRender($viewDatas, $_template = null) {
        if($this->_format == 'json' || is_null($_template)) {
            return $viewDatas;
        } else {
            return $this->render($_template, $viewDatas);
        }
    }

    /**
     * @return Response|RedirectResponse
     *
     * @throws AccessDeniedException
     */
    public function changePasswordAction()
    {
        $this->postConstruct();

        $user = $this->getUser();
        if (!is_object($user) || !$user instanceof UserInterface) {
            $this->createAccessDeniedException('This user does not have access to this section.');
        }

        $form = $this->get('fos_user.change_password.form');
        $formHandler = $this->get('fos_user.change_password.form.handler');

        $process = $formHandler->process($user);
        if ($process) {
            $this->setFlash('fos_user_success', 'change_password.flash.success');

            return $this->redirect($this->getRedirectionUrl($user));
        }

        $viewDatas = array(
            'form' => $form->createView()
        );

        return $this->viewDatasRender($viewDatas, 'ApplicationSonataUserBundle:ChangePassword:changePassword.html.'.$this->container->getParameter('fos_user.template.engine'));

    }

    /**
     * @param UserInterface $user
     *
     * @return string
     */
    protected function getRedirectionUrl(UserInterface $user)
    {
        return $this->generateUrl('sonata_user_profile_show');
    }

    /**
     * @param string $action
     * @param string $value
     */
    protected function setFlash($action, $value)
    {
        $this->get('session')->getFlashBag()->set($action, $value);
    }
}
