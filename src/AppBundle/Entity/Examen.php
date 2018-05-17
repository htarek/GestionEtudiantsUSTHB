<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Examen
 *
 * @ORM\Table(name="examen")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\ExamenRepository")
 */
class Examen
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
     * @ORM\Column(name="description", type="string", nullable=true)
     */
    
    private $description;

    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", nullable=true)
     */
    
    private $name;
    
   
    /**
     * @var int
     *
     * @ORM\Column(name="note", type="integer", nullable=true)
     */
    
    private $note;

    /**
     * @var int
     *
     * @ORM\Column(name="coefficient", type="integer", nullable=true)
     */
    
    private $coefficient;


    /**
     * @var \DateTime
     *
     * @ORM\Column(name="date_examen", type="datetime", nullable=true)
     */
    
    private $dateExamen;

    /**
     * @var bool
     *
     * @ORM\Column(name="etat", type="boolean", nullable=true)
     */
    private $etat;

    /**
    * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Section", inversedBy="examens")
    * @ORM\JoinColumn(nullable=true)
    */
    private $section;



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
     * Set dateExamen
     *
     * @param \DateTime $dateExamen
     *
     * @return Examen
     */
    public function setDateExamen($dateExamen)
    {
        $this->dateExamen = $dateExamen;

        return $this;
    }

    /**
     * Get dateExamen
     *
     * @return \DateTime
     */
    public function getDateExamen()
    {
        return $this->dateExamen;
    }

    /**
     * Set etat
     *
     * @param boolean $etat
     *
     * @return Examen
     */
    public function setEtat($etat)
    {
        $this->etat = $etat;

        return $this;
    }

    /**
     * Get etat
     *
     * @return bool
     */
    public function getEtat()
    {
        return $this->etat;
    }

    /**
     * Set section
     *
     * @param \AppBundle\Entity\Section $section
     *
     * @return Examen
     */
    public function setSection(\AppBundle\Entity\Section $section = null)
    {
        $this->section = $section;

        return $this;
    }

    /**
     * Get section
     *
     * @return \AppBundle\Entity\Section
     */
    public function getSection()
    {
        return $this->section;
    }

    /**
     * Set description
     *
     * @param string $description
     *
     * @return Examen
     */
    public function setDescription($description)
    {
        $this->description = $description;

        return $this;
    }

    /**
     * Get description
     *
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * Set name
     *
     * @param string $name
     *
     * @return Examen
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get name
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set note
     *
     * @param integer $note
     *
     * @return Examen
     */
    public function setNote($note)
    {
        $this->note = $note;

        return $this;
    }

    /**
     * Get note
     *
     * @return integer
     */
    public function getNote()
    {
        return $this->note;
    }

    /**
     * Set coefficient
     *
     * @param integer $coefficient
     *
     * @return Examen
     */
    public function setCoefficient($coefficient)
    {
        $this->coefficient = $coefficient;

        return $this;
    }

    /**
     * Get coefficient
     *
     * @return integer
     */
    public function getCoefficient()
    {
        return $this->coefficient;
    }
}
