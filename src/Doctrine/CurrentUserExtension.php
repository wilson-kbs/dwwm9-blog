<?php

namespace App\Doctrine;

use ApiPlatform\Core\Bridge\Doctrine\Orm\Extension\QueryCollectionExtensionInterface;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Extension\QueryItemExtensionInterface;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Util\QueryNameGeneratorInterface;
use App\Entity\Post;
use App\Entity\User;
use Doctrine\ORM\QueryBuilder;
use Symfony\Component\Security\Core\Security;

final class CurrentUserExtension implements QueryCollectionExtensionInterface, QueryItemExtensionInterface
{
  private $security;

  public function __construct(Security $security)
  {
    $this->security = $security;
  }

  public function applyToCollection(QueryBuilder $queryBuilder, QueryNameGeneratorInterface $queryNameGenerator, string $resourceClass, string $operationName = null): void
  {
    $this->addWhere($queryBuilder, $resourceClass);
  }

  public function applyToItem(QueryBuilder $queryBuilder, QueryNameGeneratorInterface $queryNameGenerator, string $resourceClass, array $identifiers, string $operationName = null, array $context = []): void
  {
    $this->addWhere($queryBuilder, $resourceClass, $operationName, $identifiers);
  }

  private function addWhere(QueryBuilder $queryBuilder, string $resourceClass, string $operationName = null, array $identifiers = null): void
  {

    if (Post::class !== $resourceClass || $this->security->isGranted('ROLE_ADMIN')) return;

    $rootAlias = $queryBuilder->getRootAliases()[0];

    if (($operationName == 'publish' || $operationName == 'image') && $this->security->isGranted('ROLE_REDACTOR')) {
      $user = $this->security->getUser();
      if ($user instanceof User)
        $queryBuilder
          ->join(sprintf('%s.redactor', $rootAlias), 'r')
          ->join('r.user', 'u')
          ->andWhere(sprintf('u.id = :user_id', $rootAlias))
          ->setParameter('user_id', $user->getId());

      return;
    }


    $queryBuilder
      ->andWhere(sprintf('%s.isOnline = true', $rootAlias));
    $queryBuilder
      ->join(sprintf('%s.category', $rootAlias), 'c')
      ->andWhere('c.isVisible = true');

    if ($this->security->isGranted('ROLE_USER')) return;
    else {
      $queryBuilder->andWhere('c.isAnonymousVisible = true');
    }
  }
}
