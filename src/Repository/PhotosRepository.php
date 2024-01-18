<?php

namespace App\Repository;

use App\Entity\Photos;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Photos>
 *
 * @method Photos|null find($id, $lockMode = null, $lockVersion = null)
 * @method Photos|null findOneBy(array $criteria, array $orderBy = null)
 * @method Photos[]    findAll()
 * @method Photos[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PhotosRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry, private EntityManagerInterface $em)
    {
        parent::__construct($registry, Photos::class);
    }

//    /**
//     * @return Photos[] Returns an array of Photos objects
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

         
           ->leftJoin('p.categoriesPhotos', 'c') // Perform a left join on 'categories' association
           ->andWhere('c.label != :label') // Add a WHERE clause filtering by category ID
           
           ->setParameter('label', 'photographe')
           ->orderBy('p.id', 'ASC')
        //    ->setMaxResults(10)
           ->getQuery()
           ->getResult()
       ;
    }

    public function findHome(): array
    {
       $data =  $this->createQueryBuilder('p')

         
           ->leftJoin('p.categoriesPhotos', 'c') // Perform a left join on 'categories' association
           ->andWhere('c.label != :label') // Add a WHERE clause filtering by category ID
           ->setParameter('label', 'photographe')
        //    ->orderBy('random')
        //    ->setMaxResults(6)
           ->getQuery()
           ->getResult();

       shuffle($data);
       return array_slice($data, 0, 8);
    }

   public function findOne($id): ?Photos
   {
       $qb =  $this->createQueryBuilder('p');
        //    ->select('p.url')
        $qb
            // ->select('p','ph.name as photograph')
        
           ->leftJoin('p.categoriesPhotos', 'c')
           ->leftJoin('p.id_photograph', 'ph')
           ->leftJoin('ph.image_profile', 'img')
           ->andWhere('p.id = :val')
           ->setParameter('val', $id)
        //    ->groupBy('p.id')
           ->setMaxResults(1);

    
        return $qb->getQuery()->getOneOrNullResult();
   }

   public function findByCategory($id_categories): array
   {
       return $this->createQueryBuilder('p')
                    ->leftJoin('p.categoriesPhotos', 'c')
                    ->andWhere('c.id = :val')
                    ->setParameter('val', $id_categories)
                    
                    ->getQuery()
                    ->getResult();
       
   }

   public function findWithFilter($categories, $photograph): array
   {
    //    return

        $qb = $this->createQueryBuilder('p')
                ->leftJoin('p.categoriesPhotos', 'c')
                ->leftJoin('p.id_photograph', 'ph');
              
        

        if($categories != null) {
            // dd('ok');
            $ids = [];
            foreach($categories->toArray() as $categorie) {
                $ids[] = $categorie->getId();
            }
            $qb->andWhere($qb->expr()->in('c.id', $ids));
        }

        if($photograph != null) {
          
            $qb->andWhere('ph.id = :val')
                ->setParameter('val', $photograph->getId());
        }

             

        return $qb ->getQuery()->getResult();
   }
}
