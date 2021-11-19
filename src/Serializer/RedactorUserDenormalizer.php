<?php

namespace App\Serializer;

use App\Entity\Redactor;
use App\Entity\RedactorUserInterface;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Serializer\Normalizer\ContextAwareDenormalizerInterface;
use Symfony\Component\Serializer\Normalizer\DenormalizerAwareInterface;
use Symfony\Component\Serializer\Normalizer\DenormalizerAwareTrait;
use Symfony\Component\Serializer\Normalizer\DenormalizerInterface;

class RedactorUserDenormalizer implements ContextAwareDenormalizerInterface, DenormalizerAwareInterface
{

  use DenormalizerAwareTrait;

  private const ALREADY_CALLED_DENORMALIZER = 'RedactorUserDenormalizerCalled';


  public function __construct(
    private Security $security,
    private EntityManagerInterface $em,
  ) {
  }

  public function supportsDenormalization($data, string $type, string $format = null, array $context = [])
  {
    $reflectionClass = new \ReflectionClass($type);
    $alreadyCalled = $data[self::ALREADY_CALLED_DENORMALIZER] ?? false;
    return $reflectionClass->implementsInterface(RedactorUserInterface::class) && $alreadyCalled === false;
  }


  public function denormalize($data, string $type, string $format = null, array $context = [])
  {
    $data[self::ALREADY_CALLED_DENORMALIZER] = true;
    /** @var RedactorUserInterface $obj */
    $obj = $this->denormalizer->denormalize($data, $type, $format, $context);
    /** @var User $userJT */
    // $userJWT = $this->security->getUser();
    // if ($userJWT instanceof User) {
    //   /** @var User $user */
    //   $user = $this->em->createQueryBuilder()
    //     ->select('u')
    //     ->from(User::class, 'u')
    //     ->where('u.id = :id')
    //     ->setParameter('id', $userJWT->getId())
    //     ->join('u.redactor', 'r')
    //     ->getQuery()
    //     ->getSingleResult();
    //   // var_dump($user);
    // }
    // if ($user !== null && $user->getRedactor() !== null)
    //   $obj->setUser($user);
    return $obj;
  }

  public function getAlreadyCalledKey(string $type)
  {
    return self::ALREADY_CALLED_DENORMALIZER . $type;
  }
}
