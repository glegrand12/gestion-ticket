<?php

namespace App\Repository;

use App\Entity\Tickets;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Tickets>
 */
class TicketsRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Tickets::class);
    }

    //    /**
    //     * @return Tickets[] Returns an array of Tickets objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('t')
    //            ->andWhere('t.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('t.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?Tickets
    //    {
    //        return $this->createQueryBuilder('t')
    //            ->andWhere('t.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }

    public function countTicketsByStatus(): array
    {
        return $this->createQueryBuilder('t')
            ->select('t.status, COUNT(t.id) as count')
            ->groupBy('t.status')
            ->getQuery()
            ->getResult();
    }

    public function countTicketsByPriority(): array
    {
        return $this->createQueryBuilder('t')
            ->select('t.priority, COUNT(t.id) as count')
            ->groupBy('t.priority')
            ->getQuery()
            ->getResult();
    }

    public function getTicketCountsByCreationDate(): array
    {
        return $this->createQueryBuilder('t')
            ->select("SUBSTRING(t.created_at, 1, 10) AS date, COUNT(t.id) AS count")
            ->groupBy('date')
            ->orderBy('date', 'ASC')
            ->getQuery()
            ->getResult();
    }


}
