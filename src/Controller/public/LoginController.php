<?php

namespace App\Controller\public;

use App\Form\LoginType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class LoginController extends AbstractController
{
    #[Route('/login', name: 'public_login', methods: ['GET', 'POST'])]
    public function index(AuthenticationUtils $authenticationUtils): Response
    {
        $lastUsername = $authenticationUtils->getLastUsername();

        $form = $this->createForm(LoginType::class);
        $form->get('username')->setData($lastUsername);

        return $this->render('public/login/index.html.twig', [
            'form' => $form->createView(),
            'error' => $authenticationUtils->getLastAuthenticationError(),
        ]);
    }

    #[Route('/login/success', name: 'public_login_success', methods: ['GET'])]
    public function loginSuccess(): RedirectResponse
    {
        if (!$this->getUser()) {
            return $this->redirectToRoute('public_login');
        }

        return $this->redirectToRoute('admin_dashboard_index');
    }

    /**
     * @throws \Exception
     */
    #[Route('/logout', name: 'public_logout', methods: ['GET'])]
    public function logout(): void
    {
        throw new \Exception('Don\'t forget to activate logout in security.yaml');
    }

    #[Route('/logout/success', name: 'public_logout_success', methods: ['GET'])]
    public function logoutSuccess(): RedirectResponse
    {
        return $this->redirectToRoute('public_article_index');
    }
}
