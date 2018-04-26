<?php

namespace PE\Bundle\UserBundle\Controller;

use PE\Bundle\UserBundle\Form\LoginForm;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\FormError;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Core\Security;

class SecurityController extends Controller
{
    /**
     * This action render login form
     *
     * @param Request $request
     *
     * @return Response
     */
    public function loginAction(Request $request)
    {
        $session = $request->getSession();

        $authErrorKey    = Security::AUTHENTICATION_ERROR;
        $lastUsernameKey = Security::LAST_USERNAME;

        // get the error if any (works with forward and redirect -- see below)
        if ($request->attributes->has($authErrorKey)) {
            $error = $request->attributes->get($authErrorKey);
        } elseif (null !== $session && $session->has($authErrorKey)) {
            $error = $session->get($authErrorKey);
            $session->remove($authErrorKey);
        } else {
            $error = null;
        }

        if (!$error instanceof AuthenticationException) {
            $error = null; // The value does not come from the security component.
        }

        // last username entered by the user
        $username = (null === $session) ? '' : $session->get($lastUsernameKey);

        $form = $this->createForm(LoginForm::class, ['username' => $username], ['action' => $request->getUri()]);

        if ($error) {
            $form->addError(new FormError($this->get('translator')->trans(
                $error->getMessageKey(),
                $error->getMessageData(),
                'security'
            )));
        }

        return $this->render('@PEUser/Security/login.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * This action is a placeholder used for routing
     */
    public function logoutAction()
    {
        throw new \RuntimeException('You must configure logout in your security firewall configuration.');
    }

    /**
     * This action is a placeholder used for routing
     */
    public function checkAction()
    {
        throw new \RuntimeException('You must configure check path in your security firewall configuration.');
    }
}