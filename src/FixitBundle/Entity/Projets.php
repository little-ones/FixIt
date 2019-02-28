<?php


namespace FixitBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="FixitBundle\Repository\ProjetsRepository")
 * @ORM\Entity
 * @ORM\Table(name="projets")
 */
class Projets
{
    // Génération de la table par ORM
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
    private $proprietaire;

    /**
     * @ORM\Column(type="string",length=255)
     */
    private $etat;

    /**
     * @ORM\Column(type="string",length=255)
     * @Assert\NotBlank(message="Il doit y avoir une description")
     */
    private $description;

    /**
     * @var int
     * @ORM\Column(type="integer")
     * @Assert\GreaterThan(value="0")
     */
    private $duree;

    /**
     * @ORM\Column(type="string",length=255)
     */
    private $equipe;

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param mixed $id
     * @return Projets
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
     * @return Projets
     */
    public function setTitre($titre)
    {
        $this->titre = $titre;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getProprietaire()
    {
        return $this->proprietaire;
    }

    /**
     * @param mixed $proprietaire
     * @return Projets
     */
    public function setProprietaire($proprietaire)
    {
        $this->proprietaire = $proprietaire;
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
     * @return Projets
     */
    public function setEtat($etat)
    {
        $this->etat = $etat;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * @param mixed $description
     * @return Projets
     */
    public function setDescription($description)
    {
        $this->description = $description;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getDuree()
    {
        return $this->duree;
    }

    /**
     * @param mixed $duree
     * @return Projets
     */
    public function setDuree($duree)
    {
        $this->duree = $duree;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getEquipe()
    {
        return $this->equipe;
    }

    /**
     * @param mixed $equipe
     * @return Projets
     */
    public function setEquipe($equipe)
    {
        $this->equipe = $equipe;
        return $this;
    }



}