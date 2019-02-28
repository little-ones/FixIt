<?php
/**
 * Created by PhpStorm.
 * User: Touzri
 * Date: 27/02/2019
 * Time: 13:19
 */

namespace FixitBundle\Repository;

use Doctrine\ORM\EntityRepository;

class EquipesRepository extends EntityRepository
{
    public function findEquipesQB()
    {
        $query= $this->createQueryBuilder('e');
        $query->where("e.id=:id")->setParameter('id','1');
        return $query->getQuery()->getResult();
    }

    public function findEquipesDisponibles()
    {

        $query = $this->getEntityManager()->createQuery("SELECT COUNT ( * ) FROM FixitBundle:Equipes e WHERE e.disponibilite='Disponible'");
        return $query->getResult();

    }

    public function findEquipesIndisponibles()
    {

        $query = $this->getEntityManager()->createQuery("SELECT COUNT ( * ) FROM FixitBundle:Equipes e WHERE e.disponibilite='Indisponible'");
        return $query->getResult();

    }
}