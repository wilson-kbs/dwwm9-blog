<?php

namespace App\Controller;

use App\Entity\Post;
use App\Entity\Redactor;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Security\Core\Security;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Attribute\AsController;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

#[AsController]
class PostImageController extends AbstractController
{
  public function __construct(private Security $security, private EntityManagerInterface $em,)
  {
  }

  public function __invoke(Request $request)
  {
    $file = $request->files->get('file');
    if (!$file) {
      throw new BadRequestHttpException('"file" is required');
    }
    $post = $request->attributes->get('data');
    if (!($post instanceof Post)) {
      throw new \RuntimeException('Article attendu');
    }

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


    $post->setImg($file);
    $post->setUpdatedImageAt(new \DateTime('now'));

    return $post;
  }
}
