<?php

namespace App\Service;

use App\Entity\User;
use App\Repository\UserRepository;
use InvalidArgumentException;
use Lexik\Bundle\JWTAuthenticationBundle\Services\JWTTokenManagerInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class UserService extends AbstractService
{
    public function __construct(
        private UserRepository               $userRepository,
        private ValidatorInterface           $validator,
        private UserPasswordEncoderInterface $encoder,
        private JWTTokenManagerInterface     $JWTTokenManager,
        private UserPasswordHasherInterface  $passwordHasher
    )
    {
    }

    /**
     * @param User $user
     */
    public function registerUser(User $user)
    {
        if (\count($errors = $this->validator->validate($user))) {
            throw new InvalidArgumentException($errors);
        }

        $user->setPassword($this->encoder->encodePassword($user, $user->getPassword()));
        $this->userRepository->save($user);

        return $this->JWTTokenManager->create($user);
    }

    public function connectUser(array $data): ?User
    {
        $user = $this->userRepository->findOneBy(['email' => $data['email'] ?? null]);

        if (!$user || !$this->passwordHasher->isPasswordValid($user, $data['password'] ?? '')) {
            return null;
        }

        return $user;
    }
}