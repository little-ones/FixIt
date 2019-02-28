<?php
/**
 * Created by PhpStorm.
 * User: Touzri
 * Date: 05/11/2018
 * Time: 14:53
 */

namespace FixitBundle\Repository;


use Doctrine\DBAL\DBALException;
use Doctrine\ORM\EntityRepository;

class ProjetsRepository extends EntityRepository
{
    /*public function findByProjetsQB()
    {
        $query= $this->createQueryBuilder('p');
        $query->where("p.id=:id")->setParameter('id','1');
        return $query->getQuery()->getResult();
    }*/

    public function findProjetsTermineDQL()
    {
        //$query = $this->getEntityManager()->createQuery('SELECT("p") FROM FixitBundle:Projets p WHERE p.etat="Terminé"');
        /*$em = $this->getEntityManager();
        $qb = $em->createQueryBuilder()
            ->select('*')
            ->from('projets','p')
            //getDQL
            ->getQuery();*/

        /*$conn = $this->getEntityManager()->getConnection();
        $sql = 'SELECT * FROM projets p WHERE p.etat="Terminé"';
        try {
            $stmt = $conn->prepare($sql);
        } catch (DBALException $e) {
        }
        $stmt->execute();
        return $stmt->fetchAll();*/

        /*$this->getEntityManager()->
        createQuery('SELECT p FROM FixitBundle:Projets p where p.etat="Terminé"');*/
        //return $qb->execute();


        $query = $this->getEntityManager()
            ->createQuery('SELECT p FROM  FixitBundle:Projets p');
        return $query->getResult();


    }

    public function findProjetsEnCoursDQL()
    {

        $query = $this->getEntityManager()->createQuery('SELECT p.etat FROM FixitBundle:Projets p WHERE p.etat="En cours"');
        return $query->getResult();

    }



}