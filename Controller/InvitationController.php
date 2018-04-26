<?php

namespace PE\Bundle\UserBundle\Controller;

use PE\Bundle\UserBundle\Form\InvitationCreateForm;
use PE\Bundle\UserBundle\Form\InvitationRegisterForm;
use PE\Bundle\UserBundle\Model\UserInterface;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class InvitationController extends Controller
{
    public function createAction(Request $request)
    {
        $user = $this->get('pe_user.repository.user')->createUser();

        $form = $this->createForm(InvitationCreateForm::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $user->setToken($this->get('pe_user.token_generator')->generate());

            $this->get('pe_user.repository.user')->updateUser($user);

            $this->get('pe_user.message.invitation')->sendToUser($user);
        }

        return $this->render('@PEUser/Invitation/create.html.twig');
    }

    /**
     * This action handle invitation and display form
     *
     * @param Request $request
     *
     * @return Response
     */
    public function registerAction(Request $request)
    {
        $user = $this->get('pe_user.repository.user')->findUserByToken($token = $request->get('token'));

        if (!($user instanceof UserInterface)) {
            throw new NotFoundHttpException(sprintf('The user with token "%s" does not exist', $token));
        }

        $form = $this->createForm(InvitationRegisterForm::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $user->setToken(null);

            $this->get('pe_user.repository.user')->updateUser($user);

            return $this->redirectToRoute('pe_user__invitation_success');
        }

        return $this->render('@PEUser/Invitation/register.html.twig');
    }

    /**
     * This action called if user cancel invitation
     *
     * @param Request $request
     *
     * @return Response
     */
    public function cancelAction(Request $request)
    {
        $user = $this->get('pe_user.repository.user')->findUserByToken($token = $request->get('token'));

        if (!($user instanceof UserInterface)) {
            throw new NotFoundHttpException(sprintf('The user with token "%s" does not exist', $token));
        }

        $this->get('pe_user.repository.user')->removeUser($user);

        return $this->redirectToRoute('pe_user__invitation_cancelled');
    }

    /**
     * This action called after register action
     *
     * @return Response
     */
    public function successAction()
    {
        return $this->render('@PEUser/Invitation/success.html.twig');
    }

    /**
     * This action called after cancel action
     *
     * @return Response
     */
    public function cancelledAction()
    {
        return $this->render('@PEUser/Invitation/cancelled.html.twig');
    }
}