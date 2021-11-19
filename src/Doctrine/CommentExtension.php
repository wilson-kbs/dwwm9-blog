<?php

namespace App\Doctrine;

use ApiPlatform\Core\Bridge\Doctrine\Orm\Extension\QueryCollectionExtensionInterface;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Extension\QueryItemExtensionInterface;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Util\QueryNameGeneratorInterface;
use App\Entity\Category;
use App\Entity\Comment;
use App\Entity\Post;
use App\Entity\User;
use Doctrine\ORM\QueryBuilder;
use Symfony\Component\Security\Core\Security;

final class CommentExtension implements QueryCollectionExtensionInterface, QueryItemExtensionInterface
{
  private $security;

  public function __construct(Security $security)
  {
    $this->security = $security;
  }

  public function applyToCollection(QueryBuilder $queryBuilder, QueryNameGeneratorInterface $queryNameGenerator, string $resourceClass, string $operationName = null): void
  {
    if (Comment::class !== $resourceClass || $this->security->isGranted('ROLE_ADMIN')) return;
    $this->addWhere($queryBuilder, $resourceClass);
  }

  public function applyToItem(QueryBuilder $queryBuilder, QueryNameGeneratorInterface $queryNameGenerator, string $resourceClass, array $identifiers, string $operationName = null, array $context = []): void
  {
    if (Comment::class !== $resourceClass || $this->security->isGranted('ROLE_ADMIN')) return;
    $this->addWhere($queryBuilder, $resourceClass, $operationName, $identifiers);
  }

  private function addWhere(QueryBuilder $queryBuilder, string $resourceClass, string $operationName = null, array $identifiers = null): void
  {
    $rootAlias = $queryBuilder->getRootAliases()[0];

    $queryBuilder
      ->join(sprintf('%s.post', $rootAlias), 'p')
      ->andWhere(sprintf('p.isOnline = true'));

    $queryBuilder
      ->join('p.category', 'c')
      ->andWhere('c.isVisible = true');

    if (!$this->security->isGranted('ROLE_USER')) {
      $queryBuilder->andWhere('c.isAnonymousVisible = true');
    }
  }
}
