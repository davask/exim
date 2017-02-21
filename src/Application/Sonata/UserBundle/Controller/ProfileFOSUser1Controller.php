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
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;

use Nelmio\ApiDocBundle\Annotation\ApiDoc;
use FOS\RestBundle\Controller\Annotations as Rest;
use FOS\RestBundle\View\View;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;


/**
 * This class is inspired from the FOS Profile Controller, except :
 *   - only twig is supported
 *   - separation of the user authentication form with the profile form.
 */
class ProfileFOSUser1Controller extends Controller
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
     * @Rest\View
     *
     * @ApiDoc(
     *  description="Return all speakers",
     *  requirements={
     *      {"name"="_method", "requirement"="GET"},
     *      {"name"="_locale", "requirement"="fr"},
     *      {"name"="_format", "requirement"="html|json"}
     *  },
     *  output={"class"="Application\Sonata\UserBundle\Entity\User", "groups"={"sonata_api_read"}},
     *  statusCodes={
     *      200="Returned when successful",
     *  }
     * )

     */
    public function allAction()
    {
        $this->postConstruct();

        $speakerGroupId = 1;

        $viewDatas = array(
            'speakers' => $this->repo->findUsersByGroupId($speakerGroupId),
        );

        dump($viewDatas['speakers']);

        return $this->viewDatasRender($viewDatas, 'ApplicationSonataUserBundle:Speaker:all.html.twig');

    }

    /**
     * @return Response|RedirectResponse
     *
     * @throws AccessDeniedException
     */
    public function getAction()
    {
        $this->postConstruct();

        $username = $this->request->attributes->get('username', 'me');

        $user = $this->getUser();

        if($username == 'me') {
            $speaker = $user;
        } else {
            $speaker = $this->repo->findOneByUsernameCanonical($username);
        }

        if(empty($speaker)) {
            return $this->redirect($this->generateUrl('lcdd_speaker_all'));
        }

        $site = $this->get('sonata.page.manager.site')->findOneBy(array('id'=>1));
        $page = $this->get('sonata.page.cms_manager_selector')->retrieve()
            ->getPageByRouteName($site,$this->get('request')->get('_route'));

        $viewDatas = array(
            'speaker' => $speaker,
            'page' => $page,
            'blocks' => $this->container->getParameter('lcdd.speaker.configuration.speaker_blocks'),
        );

        return $this->viewDatasRender($viewDatas, 'ApplicationSonataUserBundle:Speaker:get.html.twig');
    }

    /**
     * @return Response|RedirectResponse
     *
     * @throws AccessDeniedException
     */
    public function showAction()
    {
        $this->postConstruct();

        $user = $this->getUser();

        if (!is_object($user) || !$user instanceof UserInterface) {
            throw $this->createAccessDeniedException('This speaker does not exist.');
        }

        $viewDatas = array(
            'user' => $user,
            'blocks' => $this->container->getParameter('sonata.user.configuration.profile_blocks'),
        );

        return $this->viewDatasRender($viewDatas, 'ApplicationSonataUserBundle:Profile:show.html.twig');
    }

    /**
     * @return Response|RedirectResponse
     *
     * @throws AccessDeniedException
     */
    public function editAuthenticationAction()
    {
        $this->postConstruct();

        $user = $this->getUser();
        if (!is_object($user) || !$user instanceof UserInterface) {
            throw $this->createAccessDeniedException('This user does not have access to this section.');
        }

        $form = $this->get('sonata.user.authentication.form');
        $formHandler = $this->get('sonata.user.authentication.form_handler');

        $process = $formHandler->process($user);
        if ($process) {
            $this->setFlash('sonata_user_success', 'profile.flash.updated');

            return $this->redirect($this->generateUrl('sonata_user_profile_show'));
        }

        $viewDatas = array(
            'form' => $form->createView(),
        );

        return $this->viewDatasRender($viewDatas, 'ApplicationSonataUserBundle:Profile:edit_authentication.html.twig');

    }

    /**
     * @return Response|RedirectResponse
     *
     * @throws AccessDeniedException
     */
    public function editProfileAction()
    {
        $this->postConstruct();

        $user = $this->getUser();
        if (!is_object($user) || !$user instanceof UserInterface) {
            throw $this->createAccessDeniedException('This user does not have access to this section.');
        }

        $form = $this->get('sonata.user.profile.form');
        $formHandler = $this->get('sonata.user.profile.form.handler');

        $process = $formHandler->process($user);
        if ($process) {
            $this->setFlash('sonata_user_success', 'profile.flash.updated');

            return $this->redirect($this->generateUrl('sonata_user_profile_show'));
        }

        $viewDatas = array(
            'form' => $form->createView(),
            'breadcrumb_context' => 'user_profile',
        );

        return $this->viewDatasRender($viewDatas, 'ApplicationSonataUserBundle:Profile:edit_profile.html.twig');

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
