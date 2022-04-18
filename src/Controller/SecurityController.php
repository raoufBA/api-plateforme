<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class SecurityController extends AbstractController
{

    /**
     * @Route(path="/api/login", name="api_login", methods={"POST"})
     * @return JsonResponse
     */
    public function login(): JsonResponse
    {
        $user = $this->getUser();

        return $this->json([
            'username' => $user->getUserIdentifier(),
            'roles' => $user->getRoles()
        ]);
    }

    /**
     * @Route(path="/api/logout", name="api_logoutt", methods={"POST"})
     */
    public function logout()
    {
        return new Response('', Response::HTTP_OK);

    }
}
