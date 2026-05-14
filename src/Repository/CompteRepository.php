<?php
namespace App\Repository;

use App\Entity\Compte;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

class CompteRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Compte::class);
    }

    public function search(mixed $query = '', mixed $type = '', mixed $etat = ''): array
    {
        $qb = $this->createQueryBuilder('c')
            ->join('c.utilisateur', 'u')
            ->addSelect('u');

        $query = trim((string) $query);
        $type = trim((string) $type);
        $etat = trim((string) $etat);

        if ($query !== '') {
                $qb->andWhere('c.numeroCompte LIKE :q OR u.fullName LIKE :q OR u.email LIKE :q')
               ->setParameter('q', '%'.$query.'%');
        }
        if ($type !== '') {
            $qb->andWhere('c.typeCompte = :type')->setParameter('type', $type);
        }
        if ($etat !== '') {
            $qb->andWhere('c.etat = :etat')->setParameter('etat', $etat);
        }

        return $qb->orderBy('c.dateCreation', 'DESC')->getQuery()->getResult();
    }
}
