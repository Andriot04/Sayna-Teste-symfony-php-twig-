<?php

namespace App\Repository;

use App\Entity\Sound;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Sound|null find($id, $lockMode = null, $lockVersion = null)
 * @method Sound|null findOneBy(array $criteria, array $orderBy = null)
 * @method Sound[]    findAll()
 * @method Sound[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class SoundRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Sound::class);
    }

    // /**
    //  * @return Sound[] Returns an array of Sound objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('s')
            ->andWhere('s.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('s.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Sound
    {
        return $this->createQueryBuilder('s')
            ->andWhere('s.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
