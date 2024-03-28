<?php

namespace App\Repository;

use App\Entity\ResetPass;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<ResetPass>
 *
 * @method ResetPass|null find($id, $lockMode = null, $lockVersion = null)
 * @method ResetPass|null findOneBy(array $criteria, array $orderBy = null)
 * @method ResetPass[]    findAll()
 * @method ResetPass[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ResetPassRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ResetPass::class);
    }

    //    /**
    //     * @return ResetPass[] Returns an array of ResetPass objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('r')
    //            ->andWhere('r.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('r.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?ResetPass
    //    {
    //        return $this->createQueryBuilder('r')
    //            ->andWhere('r.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
