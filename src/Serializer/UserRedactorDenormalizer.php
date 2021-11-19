<?php

namespace App\Serializer;

use App\Entity\Post;
use App\Entity\User;
use App\Entity\UserRedactorInterface;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Serializer\Normalizer\ContextAwareDenormalizerInterface;
use Symfony\Component\Serializer\Normalizer\DenormalizerAwareInterface;
use Symfony\Component\Serializer\Normalizer\DenormalizerAwareTrait;
use Symfony\Component\Serializer\Normalizer\DenormalizerInterface;

class UserRedactorDenormalizer implements ContextAwareDenormalizerInterface, DenormalizerAwareInterface
{

  use DenormalizerAwareTrait;

  private const ALREADY_CALLED_DENORMALIZER = 'UserRedactorDenormalizerCalled';


  public function __construct(
    private Security $security,
    private EntityManagerInterface $em,
  ) {
  }

  public function supportsDenormalization($data, string $type, string $format = null, array $context = [])
  {
    $reflectionClass = new \ReflectionClass($type);
    $alreadyCalled = $data[self::ALREADY_CALLED_DENORMALIZER] ?? false;
    return $reflectionClass->implementsInterface(UserRedactorInterface::class) && $alreadyCalled === false;
  }


  public function denormalize($data, string $type, string $format = null, array $context = [])
  {
    $data[self::ALREADY_CALLED_DENORMALIZER] = true;
    /** @var UserRedactorInterface $obj */
    $obj = $this->denormalizer->denormalize($data, $type, $format, $context);
    /** @var User $userJT */
    $userJWT = $this->security->getUser();
    if ($userJWT instanceof User) {
      /** @var User | null $user */
      $user = $this->em->createQueryBuilder()
        ->select('u')
        ->from(User::class, 'u')
        ->where('u.id = :id')
        ->setParameter('id', $userJWT->getId())
        ->join('u.redactor', 'r')
        ->getQuery()
        ->getSingleResult();
    }
    if ($user !== null && $user->getRedactor() !== null)
      $obj->setRedactor($user->getRedactor());
    return $obj;
  }

  public function getAlreadyCalledKey(string $type)
  {
    return self::ALREADY_CALLED_DENORMALIZER . $type;
  }
}
