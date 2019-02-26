<?php

namespace ProBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Offre
 *
 * @ORM\Table(name="offre")
 * @ORM\Entity(repositoryClass="ProBundle\Repository\OffreRepository")
 */
class Offre
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;
    /**
     *@ORM\ManyToOne(targetEntity="ClientBundle\Entity\Service")
     *@ORM\JoinColumn(name="categorie_id",referencedColumnName="id")
     */
    private $service;
    /**
     *@ORM\Column(type="date")
     */
    private $date;

    /**
     *@ORM\ManyToOne(targetEntity="AppBundle\Entity\User")
     *@ORM\JoinColumn(name="user_id",referencedColumnName="id")
     */
    private $idPro;

    /**
     * @var int
     *
     * @ORM\Column(name="budget", type="integer")
     */
    private $budget;

    /**
     *@ORM\Column(type="string" , length=255)
     */
    private $Titre;
    /**
     *@ORM\Column(type="string" , length=255)
     */
    private $Description;

    /**
     * @return mixed
     */
    public function getService()
    {
        return $this->service;
    }

    /**
     * @param mixed $service
     */
    public function setService($service)
    {
        $this->service = $service;
    }

    /**
     * @return mixed
     */
    public function getDate()
    {
        return $this->date;
    }

    /**
     * @param mixed $date
     */
    public function setDate($date)
    {
        $this->date = $date;
    }

    /**
     * @return mixed
     */
    public function getIdPro()
    {
        return $this->idPro;
    }

    /**
     * @param mixed $idPro
     */
    public function setIdPro($idPro)
    {
        $this->idPro = $idPro;
    }



    /**
     * @return int
     */
    public function getBudget()
    {
        return $this->budget;
    }

    /**
     * @param int $budget
     */
    public function setBudget($budget)
    {
        $this->budget = $budget;
    }

    /**
     * @return mixed
     */
    public function getTitre()
    {
        return $this->Titre;
    }

    /**
     * @param mixed $Titre
     */
    public function setTitre($Titre)
    {
        $this->Titre = $Titre;
    }

    /**
     * @return mixed
     */
    public function getDescription()
    {
        return $this->Description;
    }

    /**
     * @param mixed $Description
     */
    public function setDescription($Description)
    {
        $this->Description = $Description;
    }



    /**
     * Get id
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }
}

