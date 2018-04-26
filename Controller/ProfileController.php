<?php

namespace PE\Bundle\UserBundle\Controller;

use PE\Bundle\UserBundle\Form\ProfileForm;
use PE\Bundle\UserBundle\Model\UserInterface;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;

class ProfileController extends Controller
{
    /**
     * This action displays current user profile page
     *
     * @return Response
     */
    public function showAction()
    {
        $user = $this->getUser();

        if (!($user instanceof UserInterface)) {
            throw new AccessDeniedHttpException('This user does not have access to this section.');
        }

        return $this->render('@PEUser/Profile/show.html.twig', ['user' => $user]);
    }

    /**
     * This action handle and show current user profile form
     *
     * @param Request $request
     *
     * @return Response
     */
    public function editAction(Request $request)
    {
        $user = $this->getUser();

        if (!($user instanceof UserInterface)) {
            throw new AccessDeniedHttpException('This user does not have access to this section.');
        }

        $form = $this->createForm(ProfileForm::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->get('pe_user.repository.user')->updateUser($user);

            return $this->redirectToRoute('pe_user__profile_show');
        }

        return $this->render('@PEUser/Profile/edit.html.twig', ['form' => $form]);
    }
}