<?php

namespace FixitBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
/**
 * evenement
 *
 * @ORM\Table(name="evenement")
 * @ORM\Entity(repositoryClass="FixitBundle\Repository\evenementRepository")
 */
class evenement
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
     * @var int
     * @Assert\GreaterThan(value="2")
     * @ORM\Column(name="prix", type="integer")
     */
    private $prix;

    /**
     * @var string
     * @Assert\NotBlank()
     * @ORM\Column(name="lieu", type="string", length=255)
     */
    private $lieu;

    /**
     * @var \DateTime
     *
     * @Assert\GreaterThanOrEqual("today")
     * @ORM\Column(name="date", type="datetime")
     */
    private $date;

    /**
     * @var int
     *
     * @Assert\GreaterThan(value="0")
     * @ORM\Column(name="nbre_participant", type="integer")
     */
    private $nbreParticipant;

    /**
     * @var int
     * @Assert\NotBlank()
     * @ORM\Column(name="rating", type="integer")
     */
    private $rating;



    /**
     * Get id
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set prix
     *
     * @param integer $prix
     *
     * @return evenement
     */
    public function setPrix($prix)
    {
        $this->prix = $prix;

        return $this;
    }

    /**
     * Get prix
     *
     * @return int
     */
    public function getPrix()
    {
        return $this->prix;
    }

    /**
     * Set lieu
     *
     * @param string $lieu
     *
     * @return evenement
     */
    public function setLieu($lieu)
    {
        $this->lieu = $lieu;

        return $this;
    }

    /**
     * Get lieu
     *
     * @return string
     */
    public function getLieu()
    {
        return $this->lieu;
    }

    /**
     * Set date
     *
     * @param \DateTime $date
     *
     * @return evenement
     */
    public function setDate($date)
    {
        $this->date = $date;

        return $this;
    }

    /**
     * Get date
     *
     * @return \DateTime
     */
    public function getDate()
    {
        return $this->date;
    }

    /**
     * Set nbreParticipant
     *
     * @param integer $nbreParticipant
     *
     * @return evenement
     */
    public function setNbreParticipant($nbreParticipant)
    {
        $this->nbreParticipant = $nbreParticipant;

        return $this;
    }

    /**
     * Get nbreParticipant
     *
     * @return int
     */
    public function getNbreParticipant()
    {
        return $this->nbreParticipant;
    }
    /**
     * @ORM\ManyToOne(targetEntity="FixitBundle\Entity\formation")
     * @ORM\JoinColumn(name="formation",referencedColumnName="id")
     */
    private $formation;
    /**
     * @return mixed
     */
    public function getFormation()
    {
        return $this->formation;
    }

    /**
     * @param mixed $formation
     */
    public function setformation($formation)
    {
        $this->formation = $formation;
    }
    /**
     * Set rating
     *
     * @param integer $rating
     *
     * @return evenement
     */
    public function setRating($rating)
    {
        $this->rating = $rating;

        return $this;
    }

    /**
     * Get rating
     *
     * @return int
     */
    public function getRating()
    {
        return $this->rating;
    }
    /**
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\User")
     * @ORM\JoinColumn(name="fos_user",referencedColumnName="id")
     */
    private $idUser;
    /**
     * @return mixed
     */
    public function getIdUser()
    {
        return $this->idUser;
    }

    /**
     * @param mixed $iduser
     */
    public function setIdUser($idUser)
    {
        $this->idUser = $idUser;
    }
    /**
     * @var string
     *
     * @Assert\NotBlank()
     * @ORM\Column(name="nom", type="string", length=255)
     */
    private $nom;
    /**
     * Set nom
     *
     * @param string $nom
     *
     * @return evenement
     */
    public function setnom($nom)
    {
        $this->nom = $nom;

        return $this;
    }

    /**
     * Get nom
     *
     * @return string
     */
    public function getnom()
    {
        return $this->nom;
    }




    public function __toString()
    {
        return $this->getnom();
    }
}

