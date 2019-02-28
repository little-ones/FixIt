<?php

namespace EntrepreneurBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Equipes
 *
 * @ORM\Table(name="equipes")
 * @ORM\Entity(repositoryClass="EntrepreneurBundle\Repository\EquipesRepository")
 */
class Equipes
{
    /**
     * @ORM\GeneratedValue
     * @ORM\Id
     * @ORM\Column(type="integer")
     */
    private $id;
    /**
     * @ORM\Column(type="string",length=255)
     * @Assert\NotBlank(message="Le champs titre est obligatoire")
     */
    private $titre;
    /**
     * @ORM\Column(type="string",length=255)
     */
    private $membres;

    /**
     * @ORM\Column(type="string",length=255)
     */
    private $etat;

    /**
     * @ORM\Column(type="string",length=255)
     */
    private $disponibilite;
    /**
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\User")
     * @ORM\JoinColumn (name="user_id", referencedColumnName="id")
     */
    private $proprietere;

    /**
     * @return mixed
     */
    public function getProprietere()
    {
        return $this->proprietere;
    }

    /**
     * @param mixed $proprietere
     */
    public function setProprietere($proprietere)
    {
        $this->proprietere = $proprietere;
    }

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param mixed $id
     * @return Equipes
     */
    public function setId($id)
    {
        $this->id = $id;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getTitre()
    {
        return $this->titre;
    }

    /**
     * @param mixed $titre
     * @return Equipes
     */
    public function setTitre($titre)
    {
        $this->titre = $titre;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getMembres()
    {
        return $this->membres;
    }

    /**
     * @param mixed $membres
     * @return Equipes
     */
    public function setMembres($membres)
    {
        $this->membres = $membres;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getEtat()
    {
        return $this->etat;
    }

    /**
     * @param mixed $etat
     * @return Equipes
     */
    public function setEtat($etat)
    {
        $this->etat = $etat;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getDisponibilite()
    {
        return $this->disponibilite;
    }

    /**
     * @param mixed $disponibilite
     * @return Equipes
     */
    public function setDisponibilite($disponibilite)
    {
        $this->disponibilite = $disponibilite;
        return $this;
    }

    public function __toString() {
        return $this->titre;
    }
}

