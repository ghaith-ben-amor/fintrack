<?php
namespace App\Repository;

use App\Entity\Dossier;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

class DossierRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Dossier::class);
    }

    public function search(mixed $query = ''): array
    {
        $qb = $this->createQueryBuilder('d')
            ->join('d.utilisateur', 'u')
            ->addSelect('u');

        $query = trim((string) $query);

        if ($query !== '') {
            $qb->andWhere('d.nomDossier LIKE :q OR d.description LIKE :q OR u.fullName LIKE :q OR u.email LIKE :q')
               ->setParameter('q', '%'.$query.'%');
        }

        return $qb->orderBy('d.createdAt', 'DESC')->getQuery()->getResult();
    }
}
