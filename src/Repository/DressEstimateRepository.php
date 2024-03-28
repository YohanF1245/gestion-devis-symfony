<?php

namespace App\Repository;

use App\Entity\DressEstimate;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<DressEstimate>
 *
 * @method DressEstimate|null find($id, $lockMode = null, $lockVersion = null)
 * @method DressEstimate|null findOneBy(array $criteria, array $orderBy = null)
 * @method DressEstimate[]    findAll()
 * @method DressEstimate[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class DressEstimateRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, DressEstimate::class);
    }

    //    /**
    //     * @return DressEstimate[] Returns an array of DressEstimate objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('d')
    //            ->andWhere('d.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('d.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?DressEstimate
    //    {
    //        return $this->createQueryBuilder('d')
    //            ->andWhere('d.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
