<?php
namespace App\Repository;

use App\Entity\Categorie;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

class CategorieRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Categorie::class);
    }

    public function search(mixed $query = ''): array
    {
        $qb = $this->createQueryBuilder('c');

        $query = trim((string) $query);

        if ($query !== '') {
            $qb->andWhere('c.nomCategorie LIKE :q OR c.description LIKE :q')
               ->setParameter('q', '%'.$query.'%');
        }

        return $qb->orderBy('c.nomCategorie', 'ASC')->getQuery()->getResult();
    }
}
