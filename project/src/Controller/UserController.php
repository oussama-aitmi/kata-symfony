<?php

namespace App\Controller;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Lexik\Bundle\JWTAuthenticationBundle\Services\JWTTokenManagerInterface;
use App\Service\UserService;

class AccountController extends AbstractController
{
    #[Route('/account', name: 'create_user', methods: ['POST'])]
    public function createUser(
        Request     $request,
        UserService $userService
    ): JsonResponse
    {
        $data = json_decode($request->getContent(), true);

        $user = new User();
        $user->setUsername($data['username']);
        $user->setFirstname($data['firstname']);
        $user->setEmail($data['email']);
        $user->setPassword($data['password']);

        $token = $userService->registerUser($user);

        return $this->json([
            'token' => $token
        ], 201
        );
    }

    #[Route('/token', name: 'app_login', methods: ['POST'])]
    public function login(
        Request                  $request,
        UserService              $userService,
        JWTTokenManagerInterface $JWTManager
    ): JsonResponse
    {
        $data = json_decode($request->getContent(), true);

        $user = $userService->connectUser($data);

        if (!$user) {
            return $this->json(['error' => 'Email ou mot de passe invalide'], 401);
        }

        $token = $JWTManager->create($user);

        return $this->json([
            'token' => $token
        ]);
    }
}
