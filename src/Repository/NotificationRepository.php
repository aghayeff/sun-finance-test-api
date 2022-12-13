<?php

namespace App\Repository;

use App\Entity\Client;
use App\Entity\Notification;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Request;

/**
 * @extends ServiceEntityRepository<Notification>
 *
 * @method Notification|null find($id, $lockMode = null, $lockVersion = null)
 * @method Notification|null findOneBy(array $criteria, array $orderBy = null)
 * @method Notification[]    findAll()
 * @method Notification[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class NotificationRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Notification::class);
    }

    public function save(Notification $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Notification $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function paginate(int $limit, int $offset, Request $request)
    {
        $query = $this->createQueryBuilder('n')
            ->innerJoin(Client::class, 'c', 'WITH', 'n.client = c.id')
            ->orderBy('n.id', 'DESC')
            ->setMaxResults($limit)
            ->setFirstResult($offset);

        if (intval($request->get('clientId')) > 0) {
            $query->andWhere('n.client = :client')
                ->setParameter('client', $request->get('clientId'));
        }

        return $query->getQuery()->getResult();
    }
}
