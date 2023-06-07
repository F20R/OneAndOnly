<?php

namespace App\Repository;

use App\Entity\Chat;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Chat>
 *
 * @method Chat|null find($id, $lockMode = null, $lockVersion = null)
 * @method Chat|null findOneBy(array $criteria, array $orderBy = null)
 * @method Chat[]    findAll()
 * @method Chat[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ChatRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Chat::class);
    }

    public function save(Chat $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Chat $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

//    /**
//     * @return Chat[] Returns an array of Chat objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('c')
//            ->andWhere('c.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('c.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Chat
//    {
//        return $this->createQueryBuilder('c')
//            ->andWhere('c.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }

public function findByReceptor($idUser): array{
        return $this->createQueryBuilder('m')
            ->andWhere('m.id_receptor = :val')
            ->orWhere('m.id_receptor = :val')
            ->setParameter('val', $idUser)
            ->orderBy('m.fecha', 'DESC')
            ->getQuery()
            ->getResult();

}
    public function findByEmisor($idUser): array{
        return $this->createQueryBuilder('m')
            ->andWhere('m.id_emisor = :val')
            ->orWhere('m.id_emisor = :val')
            ->setParameter('val', $idUser)
            ->orderBy('m.fecha', 'ASC')
            ->getQuery()
            ->getResult();



    }

    public function findByEmisorReceptor($id_emisor, $id_receptor): array{
        $query = 'select * from chat  where id_emisor_id in (:idEmisor,:idReceptor) and id_receptor_id in (:idEmisor,:idReceptor)';

        $s = $this->getEntityManager()->getConnection()->prepare($query);

        return $s->executeQuery([
                'idEmisor' => $id_emisor,
                'idReceptor' => $id_receptor])->fetchAllAssociative();
    }

    public function findByUsuario($idChat): array{
        return $this->createQueryBuilder('m')
            ->andWhere('m.id = :val')
            ->setParameter('val', $idChat)
            ->orderBy('m.fecha', 'DESC')
            ->getQuery()
            ->getResult();

    }


}
