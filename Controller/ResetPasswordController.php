<?php

namespace PE\Bundle\UserBundle\Controller;

use PE\Bundle\UserBundle\Form\ResetPasswordForm;
use PE\Bundle\UserBundle\Form\ResetPasswordRequestForm;
use PE\Bundle\UserBundle\Form\TokenForm;
use PE\Bundle\UserBundle\Model\UserInterface;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class ResetPasswordController extends Controller
{
    /**
     * This action handle reset password request form
     *
     * @param Request $request
     *
     * @return Response
     */
    public function requestAction(Request $request)
    {
        $form = $this->createForm(ResetPasswordRequestForm::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            /* @var $user UserInterface */
            $user = $form->get('identity')->getData();
            $user->setToken($this->get('pe_user.token_generator')->generate());

            $this->get('pe_user.repository.user')->updateUser($user);

            return $this->redirectToRoute('pe_user__reset_password_requested');
        }

        return $this->render('@PEUser/ResetPassword/request.html.twig', ['form' => $form->createView()]);
    }

    /**
     * This action handle requested token form
     *
     * @param Request $request
     *
     * @return Response
     */
    public function requestedAction(Request $request)
    {
        $form = $this->createForm(TokenForm::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $user = $this->get('pe_user.repository.user')->findUserByToken($token = $form->get('token')->getData());

            if ($user instanceof UserInterface) {
                return $this->redirectToRoute('pe_user__reset_password_reset', ['token' => $token]);
            }
        }

        return $this->render('@PEUser/ResetPassword/requested.html.twig');
    }

    /**
     * This action handle reset password form
     *
     * @param Request $request
     *
     * @return Response
     */
    public function resetAction(Request $request)
    {
        $user = $this->get('pe_user.repository.user')->findUserByToken($token = $request->get('token'));

        if (!($user instanceof UserInterface)) {
            return $this->redirectToRoute('pe_user__security_login');
        }

        $form = $this->createForm(ResetPasswordForm::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->get('pe_user.repository.user')->updateUser($user);

            return $this->redirectToRoute('pe_user__security_login');
        }

        return $this->render('@PEUser/ResetPassword/reset.html.twig');
    }
}