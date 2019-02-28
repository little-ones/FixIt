<?php

namespace CabinetBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Participation
 *
 * @ORM\Table(name="participation")
 * @ORM\Entity(repositoryClass="CabinetBundle\Repository\ParticipationRepository")
 */
class Participation
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
     * Get id
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }
    /**
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\User")
     * @ORM\JoinColumn(name="user",referencedColumnName="id")
     */
    private $user;
    /**
     * @return mixed
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * @param mixed $user
     */
    public function setuser($user)
    {
        $this->user = $user;
    }
    /**
     * @ORM\ManyToOne(targetEntity="CabinetBundle\Entity\Evenement")
     * @ORM\JoinColumn(name="evenement",referencedColumnName="id")
     */
    private $evenement;
    /**
     * @return mixed
     */
    public function getEvenement()
    {
        return $this->evenement;
    }

    /**
     * @param mixed $evenement
     */
    public function setevenement($evenement)
    {
        $this->evenement = $evenement;
    }
}

