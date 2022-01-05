<?php

namespace App\Repository;

use App\Entity\TentativeLogin;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method TentativeLogin|null find($id, $lockMode = null, $lockVersion = null)
 * @method TentativeLogin|null findOneBy(array $criteria, array $orderBy = null)
 * @method TentativeLogin[]    findAll()
 * @method TentativeLogin[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TentativeLoginRepository extends ServiceEntityRepository
{
    const DELAY_IN_MINUTES = 1;
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, TentativeLogin::class);
    }

    // /**
    //  * @return TentativeLogin[] Returns an array of TentativeLogin objects
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
    public function findOneBySomeField($value): ?TentativeLogin
    {
        return $this->createQueryBuilder('t')
            ->andWhere('t.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */

    public function countRecentLoginAttempts(string $email): int
    {
        $timeAgo = new \DateTimeImmutable(sprintf('-%d minutes', self::DELAY_IN_MINUTES));

        return $this->createQueryBuilder('la')
            ->select('COUNT(la)')
            ->where('la.date >= :date')
            ->andWhere('la.email = :email')
            ->getQuery()
            ->setParameters([
                'date' => $timeAgo,
                'email' => $email,
            ])
            ->getSingleScalarResult()
            ;
    }
}
