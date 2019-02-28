<?php

namespace ClientBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints\Date;
use Vich\UploaderBundle\Mapping\Annotation as Vich;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\HttpFoundation\File\UploadedFile;
/**
 * Service
 *
 * @ORM\Table(name="service")
 * @ORM\Entity(repositoryClass="ClientBundle\Repository\ServiceRepository")
 * @Vich\Uploadable
 */
class Service
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
     * @var string
     *
     * @ORM\Column(name="categorie", type="string", length=255)
     */
    private $categorie;

    /**
     *@ORM\ManyToOne(targetEntity="AppBundle\Entity\User")
     *@ORM\JoinColumn(name="Pro_id",referencedColumnName="id")
     */
    private $idUser;

    /**
     * NOTE: This is not a mapped field of entity metadata, just a simple property.
     *
     * @Vich\UploadableField(mapping="product_image", fileNameProperty="imageName")
     *
     * @var File
     */
    private $imageFile;
    /**
     * @var date
     *
     * @ORM\Column(name="date", type="date")
     */
    private $date;
    /**
     * @ORM\Column(type="string", length=255)
     *
     * @var string
     */
    private $imageName;
    /**
     * @var string
     *
     * @ORM\Column(name="description", type="string", length=255)
     */
    private $description;

    public function setImageFile(File $imageFile = null)
    {
        $this->imageFile = $imageFile;


    }

    public function getImageFile()
    {
        return $this->imageFile;
    }

    public function setImageName( $imageName)
    {
        $this->imageName = $imageName;
    }

    public function getImageName()
    {
        return $this->imageName;
    }
    /**
     * @return mixed
     */
    public function getIdUser()
    {
        return $this->idUser;
    }

    /**
     * @param mixed $idUser
     */
    public function setIdUser($idUser)
    {
        $this->idUser = $idUser;
    }

    /**
     * @return date
     */
    public function getDate()
    {
        return $this->date;
    }

    /**
     * @param date $date
     */
    public function setDate($date)
    {
        $this->date = $date;
    }


    /**
     * Get id
     *
     * @return int
     */
    /**
     * @return mixed
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * @param mixed $description
     */
    public function setDescription($description)
    {
        $this->description = $description;
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

    /**
     * Set categorie
     *
     * @param string $categorie
     *
     * @return Service
     */
    public function setCategorie($categorie)
    {
        $this->categorie = $categorie;

        return $this;
    }

    /**
     * Get categorie
     *
     * @return string
     */
    public function getCategorie()
    {
        return $this->categorie;
    }
}

