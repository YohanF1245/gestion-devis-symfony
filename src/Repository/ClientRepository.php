<?php

namespace App\Repository;

use App\Entity\Client;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bridge\Doctrine\Types\UuidType;

/**
 * @extends ServiceEntityRepository<Client>
 *
 * @method Client|null find($id, $lockMode = null, $lockVersion = null)
 * @method Client|null findOneBy(array $criteria, array $orderBy = null)
 * @method Client[]    findAll()
 * @method Client[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ClientRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Client::class);
    }
    public function countWhere($userId)
    {
        $qb = $this->createQueryBuilder('c')
            ->where('c.user_id = :userId')
            ->andWhere('c.name LIKE :likeName')
            ->setParameter('userId', $userId, UuidType::NAME)
            ->setParameter('likeName', '%han%');
        $query = $qb->getQuery();
        dd($query->execute());
        // $userId = $this->getUser()->getId();
        // $result = $entityManagerInterface->getRepository("client")->createQueryBuilder('c')
        //     ->where('o.UserId = :userId')
        //     ->andWhere('o.name LIKE :likeName')
        //     ->setParameter('userId', $userId)
        //     ->setParameter('likeName', 'han')
        //     ->getQuery()
        //     ->getResult();
        // dd($result);
    }
    //    /**
    //     * @return Client[] Returns an array of Client objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('c')
    //            ->andWhere('c.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('c.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?Client
    //    {
    //        return $this->createQueryBuilder('c')
    //            ->andWhere('c.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
