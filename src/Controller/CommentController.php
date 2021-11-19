<?php

namespace App\Controller;

use App\Entity\Post;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Security\Core\Security;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpKernel\Attribute\AsController;

#[AsController]
class CommentController extends AbstractController
{
  public function __construct(
    private Security $security,
    private EntityManagerInterface $em,
  ) {
  }

  public function __invoke(Post $data, $test)
  {
    var_dump($data, $test);
    // $data->setIsOnline(true);
    // $this->em->persist($data);

    // $this->em->flush();
    // // dd($data);
    // $user = $this->security->getUser();
    // // dd($user);
    // return $user;
  }
}
