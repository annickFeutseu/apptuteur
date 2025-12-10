<?php

namespace App\Repository;

use App\Entity\Visite;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Visite>
 */
class VisiteRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Visite::class);
    }

    public function findByTuteurAndStatutSorted($tuteur, ?string $statut = null, ?string $order = 'ASC')
    {
        $qb = $this->createQueryBuilder('v')
            ->where('v.tuteur = :tuteur')
            ->setParameter('tuteur', $tuteur);

        if ($statut && $statut !== 'Toutes') {
            $qb->andWhere('v.statut = :statut')
            ->setParameter('statut', $statut);
        }

        $qb->orderBy('v.date', $order);

        return $qb->getQuery()->getResult();
    }


    //    /**
    //     * @return Visite[] Returns an array of Visite objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('v')
    //            ->andWhere('v.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('v.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?Visite
    //    {
    //        return $this->createQueryBuilder('v')
    //            ->andWhere('v.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
