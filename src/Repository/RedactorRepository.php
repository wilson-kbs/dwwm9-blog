<?php

namespace App\Repository;

use App\Entity\Redactor;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Redactor|null find($id, $lockMode = null, $lockVersion = null)
 * @method Redactor|null findOneBy(array $criteria, array $orderBy = null)
 * @method Redactor[]    findAll()
 * @method Redactor[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class RedactorRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Redactor::class);
    }

    // /**
    //  * @return Redactor[] Returns an array of Redactor objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('r')
            ->andWhere('r.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('r.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Redactor
    {
        return $this->createQueryBuilder('r')
            ->andWhere('r.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
