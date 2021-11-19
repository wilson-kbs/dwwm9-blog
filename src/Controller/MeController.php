<?php

namespace App\Controller;

use Symfony\Component\Security\Core\Security;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpKernel\Attribute\AsController;

#[AsController]
class MeController extends AbstractController
{
  public function __construct(private Security $security)
  {
  }

  public function __invoke()
  {
    $user = $this->security->getUser();
    // dd($user);
    return $user;
  }
}
