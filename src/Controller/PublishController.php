<?php

namespace App\Controller;

use App\Entity\Post;
use App\Entity\Redactor;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Security\Core\Security;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Attribute\AsController;

#[AsController]
class PublishController extends AbstractController
{
  public function __construct(
    private Security $security,
    private EntityManagerInterface $em,
  ) {
  }

  public function __invoke(Post $data)
  {
    if (!$this->security->isGranted('ROLE_ADMIN') && !$this->security->isGranted('ROLE_REDACTOR')) {
      return new JsonResponse(['status' => 403, 'message' => 'Sorry! You have not permission'], 403);
    }
    /** @var User $user */
    $user = $this->security->getUser();

    $redactor = $this->em->createQueryBuilder()
      ->select('u')
      ->from(Redactor::class, 'u')
      ->where('u.user = :user_id')
      ->setParameter('user_id', $user->getId())
      ->getQuery()
      ->getResult();

    if (count($redactor) == 0) {
      return new JsonResponse(['status' => 403, 'message' => 'Sorry! You have not permission'], 403);
    }

    $data->setIsOnline(true);

    $this->em->persist($data);

    $this->em->flush();

    return $data;
  }
}
