<?php

namespace App\Repository;

use App\Entity\LignePanier;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<LignePanier>
 */
class LignePanierRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, LignePanier::class);
    }

    public function save(LignePanier $lignePanier): void
    {
        $this->getEntityManager()->persist($lignePanier);
        $this->getEntityManager()->flush();
    }

    public function remove(LignePanier $lignePanier): void
    {
        $this->getEntityManager()->remove($lignePanier);
        $this->getEntityManager()->flush();
    }
}
