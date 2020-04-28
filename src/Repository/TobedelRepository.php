<?php

namespace App\Repository;

use App\Entity\Tobedel;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Tobedel|null find($id, $lockMode = null, $lockVersion = null)
 * @method Tobedel|null findOneBy(array $criteria, array $orderBy = null)
 * @method Tobedel[]    findAll()
 * @method Tobedel[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TobedelRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Tobedel::class);
    }

    // /**
    //  * @return Tobedel[] Returns an array of Tobedel objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('t')
            ->andWhere('t.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('t.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Tobedel
    {
        return $this->createQueryBuilder('t')
            ->andWhere('t.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
