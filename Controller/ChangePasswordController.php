<?php

namespace PE\Bundle\UserBundle\Controller;

use PE\Bundle\UserBundle\Form\ChangePasswordForm;
use PE\Bundle\UserBundle\Model\UserInterface;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;

class ChangePasswordController extends Controller
{
    public function changePasswordAction(Request $request)
    {
        $user = $this->getUser();

        if (!($user instanceof UserInterface)) {
            throw new AccessDeniedHttpException('This user does not have access to this section.');
        }

        $form = $this->createForm(ChangePasswordForm::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->get('pe_user.repository.user')->updateUser($user);

            return $this->redirectToRoute('pe_user__profile_show');
        }

        return $this->render('@PEUser/ChangePassword/change_password.html.twig', ['form' => $form->createView()]);
    }
}