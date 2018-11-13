<?php

namespace Sioweb\DummyBundle\Repository;

use Doctrine\ORM\EntityRepository;

class DummyRepository extends EntityRepository
{
    public function findAll(array $orderBy = [])
    {
        return $this->findBy([], $orderBy);
    }

    public function findWhatEverYouWant($parameters) {
        // Ein Beispiel mit dem ein Artikel gefunden werden kann der
        // zwischen Zeit:Start und Zeit:Ende aktiv ist.
        return $this->createQueryBuilder('t')
            ->andWhere("t.start = ''  OR t.start <= :start")
            ->andWhere("t.stop = ''  OR t.stop <= :stop")
            ->andWhere("t.published=1")
                ->setParameter('start', $start)
                ->setParameter('stop', $stop)
                    ->getQuery()
                    ->execute();
    }
}