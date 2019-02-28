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
     * @ORM\ManyToOne(targetEntity="ClientBundle\Entity\Service")
     * @ORM\JoinColumn (name="service_id", referencedColumnName="id")
     */
    private $service;
    /**
     *@ORM\Column(type="date" , length=255)
     */
    private $dateAjout;
    /**
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\User")
     * @ORM\JoinColumn (name="user_id", referencedColumnName="id")
     */
    private $User;
    /**
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\User")
     * @ORM\JoinColumn (name="Client_id", referencedColumnName="id")
     */
    private $Client;
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
    public function getDateAjout()
    {
        return $this->dateAjout;
    }

    /**
     * @param mixed $dateAjout
     */
    public function setDateAjout($dateAjout)
    {
        $this->dateAjout = $dateAjout;
    }



    /**
     * @return mixed
     */
    public function getClient()
    {
        return $this->Client;
    }

    /**
     * @param mixed $Client
     */
    public function setClient($Client)
    {
        $this->Client = $Client;
    }



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
    public function getUser()
    {
        return $this->User;
    }

    /**
     * @param mixed $User
     */
    public function setUser($User)
    {
        $this->User = $User;
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

