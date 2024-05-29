<?php

namespace App\Repository;

use App\Entity\EstimatePerformanceLink;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<EstimatePerformanceLink>
 *
 * @method EstimatePerformanceLink|null find($id, $lockMode = null, $lockVersion = null)
 * @method EstimatePerformanceLink|null findOneBy(array $criteria, array $orderBy = null)
 * @method EstimatePerformanceLink[]    findAll()
 * @method EstimatePerformanceLink[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class EstimatePerformanceLinkRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, EstimatePerformanceLink::class);
    }

    //    /**
    //     * @return EstimatePerformanceLink[] Returns an array of EstimatePerformanceLink objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('e')
    //            ->andWhere('e.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('e.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?EstimatePerformanceLink
    //    {
    //        return $this->createQueryBuilder('e')
    //            ->andWhere('e.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
