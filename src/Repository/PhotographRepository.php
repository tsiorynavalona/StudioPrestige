<?php

namespace App\Repository;

use App\Entity\Photograph;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Photograph>
 *
 * @method Photograph|null find($id, $lockMode = null, $lockVersion = null)
 * @method Photograph|null findOneBy(array $criteria, array $orderBy = null)
 * @method Photograph[]    findAll()
 * @method Photograph[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PhotographRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Photograph::class);
    }

//    /**
//     * @return Photograph[] Returns an array of Photograph objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('p')
//            ->andWhere('p.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('p.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }
    public function findAll(): array
    {
        return $this->createQueryBuilder('p')
           
            ->getQuery()
            ->getResult()
        ;
    }

   public function findOneById($value): ?Photograph
   {
       return $this->createQueryBuilder('p')
           ->andWhere('p.id = :id')
           ->setParameter('id', $value)
           ->getQuery()
           ->getOneOrNullResult()
       ;
   }
}
