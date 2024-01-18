<?php

namespace App\Repository;

use App\Entity\CategoriesPhotos;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<CategoriesPhotos>
 *
 * @method CategoriesPhotos|null find($id, $lockMode = null, $lockVersion = null)
 * @method CategoriesPhotos|null findOneBy(array $criteria, array $orderBy = null)
 * @method CategoriesPhotos[]    findAll()
 * @method CategoriesPhotos[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CategoriesPhotosRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, CategoriesPhotos::class);
    }

//    /**
//     * @return CategoriesPhotos[] Returns an array of CategoriesPhotos objects
//     */
   public function findAll(): array
   {
       return $this->createQueryBuilder('c')
           ->andWhere('c.label != :val')
           ->setParameter('val', 'photographe')
           ->orderBy('c.id', 'ASC')
        //    ->setMaxResults(10)
           ->getQuery()
           ->getResult()
       ;
   }
   public function findPhotographCategory(): ?CategoriesPhotos
   {
       return $this->createQueryBuilder('c')
           ->where('c.label = :val')
           ->orWhere('c.label = :val2')
           ->setParameter('val', 'photographe')
           ->setParameter('val2', 'photograph')
        //    ->orderBy('c.id', 'ASC')
        //    ->setMaxResults(10)
            // ->limit(1)
           ->getQuery()
           ->getOneOrNullResult();
       ;
   }
   public function findAllWithoutPhotographCategory($page = 'front'): array
   {

       $qb = $this->createQueryBuilder('c')
       
           ->where('c.label != :val')
           ->andWhere('c.label != :val2')
           ->setParameter('val', 'photographe')
           ->setParameter('val2', 'photograph');
        if($page == 'front') {
            $qb->innerJoin('c.photos', 'p');
        }

        return $qb->getQuery()
                 ->getResult();
   }
   public function findByOne($id): CategoriesPhotos
   {
       return $this->createQueryBuilder('c')
           ->andWhere('c.id = :id')
           ->setParameter('id', $id)
           ->getQuery()
           ->getOneOrNullResult()
       ;
   }

//    public function findOneBySomeField($value): ?CategoriesPhotos
//    {
//        return $this->createQueryBuilder('c')
//            ->andWhere('c.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
