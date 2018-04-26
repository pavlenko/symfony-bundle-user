<?php

namespace PE\Bundle\UserBundle\Controller;

use PE\Bundle\UserBundle\Form\RegistrationForm;
use PE\Bundle\UserBundle\Form\TokenForm;
use PE\Bundle\UserBundle\Model\UserInterface;
use PE\Bundle\UserBundle\Repository\UserRepositoryInterface;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class RegistrationController extends Controller
{
    /**
     * @var UserRepositoryInterface
     */
    private $userRepository;

    /**
     * @param UserRepositoryInterface $userRepository
     */
    public function __construct(UserRepositoryInterface $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    public function registerAction(Request $request)
    {
        $user = $this->userRepository->createUser();

        $form = $this->createForm(RegistrationForm::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->userRepository->updateUser($user);

            $confirmMessage = $this->get('pe_user.message.register_confirm');
            $confirmMessage->sendToUser($user);

            return $this->redirectToRoute('pe_user__registration_registered');
        }

        return $this->render('@PEUser/Registration/register.html.twig', ['form' => $form->createView()]);
    }

    public function registeredAction(Request $request)
    {
        $form = $this->createForm(TokenForm::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            return $this->redirectToRoute('pe_user__registration_confirm', ['token' => $form->get('token')->getData()]);
        }

        return $this->render('@PEUser/Registration/registered.html.twig', ['form' => $form->createView()]);
    }

    public function confirmAction(Request $request, $token)
    {
        $user = $this->userRepository->findUserByToken($token);

        if (!($user instanceof UserInterface)) {
            throw new NotFoundHttpException();
        }

        $user->setToken(null);
        $user->setEnabled(true);

        $this->userRepository->updateUser($user);

        //TODO automatically log in new user

        return $this->redirectToRoute('pe_user__registration_confirmed');
    }

    public function confirmedAction(Request $request)
    {
        return $this->render('@PEUser/Registration/confirmed.html.twig');
    }
}