<?php

namespace App\Repository;

use App\Entity\DatosPedido;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<DatosPedido>
 *
 * @method DatosPedido|null find($id, $lockMode = null, $lockVersion = null)
 * @method DatosPedido|null findOneBy(array $criteria, array $orderBy = null)
 * @method DatosPedido[]    findAll()
 * @method DatosPedido[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class DatosPedidoRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, DatosPedido::class);
    }

//    /**
//     * @return DatosPedido[] Returns an array of DatosPedido objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('d')
//            ->andWhere('d.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('d.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?DatosPedido
//    {
//        return $this->createQueryBuilder('d')
//            ->andWhere('d.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
