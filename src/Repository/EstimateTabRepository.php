<?php

namespace App\Repository;

use App\Entity\EstimateTab;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<EstimateTab>
 *
 * @method EstimateTab|null find($id, $lockMode = null, $lockVersion = null)
 * @method EstimateTab|null findOneBy(array $criteria, array $orderBy = null)
 * @method EstimateTab[]    findAll()
 * @method EstimateTab[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class EstimateTabRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, EstimateTab::class);
    }

//    /**
//     * @return EstimateTab[] Returns an array of EstimateTab objects
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

//    public function findOneBySomeField($value): ?EstimateTab
//    {
//        return $this->createQueryBuilder('e')
//            ->andWhere('e.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
