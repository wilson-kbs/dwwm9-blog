<?php

namespace App\Controller;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Security\Core\Security;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Attribute\AsController;
use Symfony\Component\Routing\Annotation\Route;

use ApiPlatform\Core\Validator\ValidatorInterface;
use Lexik\Bundle\JWTAuthenticationBundle\Services\JWTTokenManagerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;


#[AsController]
class SignUpController extends AbstractController
{
  public function __construct(
    private ValidatorInterface $validator,
    private EntityManagerInterface $em,
    private JWTTokenManagerInterface $jwtManager,
    private UserPasswordHasherInterface $passwordHasher
  ) {
  }

  public function __invoke(User $data, Request $request)
  {

    $user = $data;
    // Validation user
    $this->validator->validate($user);


    $usersItems = $this->em->createQueryBuilder()
      ->select('u')
      ->from(User::class, 'u')
      ->where('u.username = :username')
      ->orWhere('u.email = :email')
      ->setParameter('username', $user->getUsername())
      ->setParameter('email', $user->getEmail())
      ->getQuery()
      ->getResult();

    if (count($usersItems) > 0) {
      return new JsonResponse(['status' => 400, 'message' => 'Invalid User'], 400);
    }

    $hashedPassword = $this->passwordHasher->hashPassword(
      $user,
      $data->getPassword()
    );
    $user->setPassword($hashedPassword);

    $this->em->persist($user);
    $this->em->flush();

    return new JsonResponse(['token' => $this->jwtManager->create($user)]);

    // dd($request->getContent(), $data);
  }
}
