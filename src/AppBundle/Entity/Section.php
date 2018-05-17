<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Section
 *
 * @ORM\Table(name="section")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\SectionRepository")
 */
class Section
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
     * @ORM\Column(name="name", type="string", length=255)
     */
    private $name;

    /**
     * @var string
     *
     * @ORM\Column(name="annee", type="string", length=20, nullable=true)
     */
    private $annee;

    /**
     * @var string
     *
     * @ORM\Column(name="email", type="string", length=255, nullable=true)
     */
    private $email;

    /**
     * @var int
     *
     * @ORM\Column(name="sommeCoeffs", type="integer", nullable=true)
     */
    private $sommeCoeffs;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="dateFin", type="date", nullable=true)
     */
    private $dateFin;

    /**
    * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Module")
    * @ORM\JoinColumn(nullable=true)
    */
    private $module;

    /**
    * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Specialite")
    * @ORM\JoinColumn(nullable=true)
    */
    private $specialite;

    /**
    * @ORM\OneToMany(targetEntity="AppBundle\Entity\Groupe", mappedBy="section",  cascade={"persist", "remove"})
    */
    private $groupes;

    /**
    * @ORM\OneToMany(targetEntity="AppBundle\Entity\Examen", mappedBy="section", cascade={"persist", "remove"})
    */
    private $examens;

    /**
    * @ORM\OneToMany(targetEntity="AppBundle\Entity\Message_section", mappedBy="section", cascade={"remove"})
    */
    private $messages;


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
     * Set name
     *
     * @param string $name
     *
     * @return Section
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
     * Set annee
     *
     * @param string $annee
     *
     * @return Section
     */
    public function setAnnee($annee)
    {
        $this->annee = $annee;

        return $this;
    }

    /**
     * Get annee
     *
     * @return string
     */
    public function getAnnee()
    {
        return $this->annee;
    }

    /**
     * Set email
     *
     * @param string $email
     *
     * @return Section
     */
    public function setEmail($email)
    {
        $this->email = $email;

        return $this;
    }

    /**
     * Get email
     *
     * @return string
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * Set dateFin
     *
     * @param \DateTime $dateFin
     *
     * @return Section
     */
    public function setDateFin($dateFin)
    {
        $this->dateFin = $dateFin;

        return $this;
    }

    /**
     * Get dateFin
     *
     * @return \DateTime
     */
    public function getDateFin()
    {
        return $this->dateFin;
    }

    /**
     * Set module
     *
     * @param \AppBundle\Entity\Module $module
     *
     * @return Section
     */
    public function setModule(\AppBundle\Entity\Module $module = null)
    {
        $this->module = $module;

        return $this;
    }

    /**
     * Get module
     *
     * @return \AppBundle\Entity\Module
     */
    public function getModule()
    {
        return $this->module;
    }

    /**
     * Set specialite
     *
     * @param \AppBundle\Entity\Specialite $specialite
     *
     * @return Section
     */
    public function setSpecialite(\AppBundle\Entity\Specialite $specialite = null)
    {
        $this->specialite = $specialite;

        return $this;
    }

    /**
     * Get specialite
     *
     * @return \AppBundle\Entity\Specialite
     */
    public function getSpecialite()
    {
        return $this->specialite;
    }
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->groupes = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Add groupe
     *
     * @param \AppBundle\Entity\Groupe $groupe
     *
     * @return Section
     */
    public function addGroupe(\AppBundle\Entity\Groupe $groupe)
    {
        $this->groupes[] = $groupe;

        return $this;
    }

    /**
     * Remove groupe
     *
     * @param \AppBundle\Entity\Groupe $groupe
     */
    public function removeGroupe(\AppBundle\Entity\Groupe $groupe)
    {
        $this->groupes->removeElement($groupe);
    }

    /**
     * Get groupes
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getGroupes()
    {
        return $this->groupes;
    }

    /**
     * Add examen
     *
     * @param \AppBundle\Entity\Examen $examen
     *
     * @return Section
     */
    public function addExamen(\AppBundle\Entity\Examen $examen)
    {
        $this->examens[] = $examen;

        return $this;
    }

    /**
     * Remove examen
     *
     * @param \AppBundle\Entity\Examen $examen
     */
    public function removeExamen(\AppBundle\Entity\Examen $examen)
    {
        $this->examens->removeElement($examen);
    }

    /**
     * Get examens
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getExamens()
    {
        return $this->examens;
    }

    /**
     * Add message
     *
     * @param \AppBundle\Entity\Message_section $message
     *
     * @return Section
     */
    public function addMessage(\AppBundle\Entity\Message_section $message)
    {
        $this->messages[] = $message;

        return $this;
    }

    /**
     * Remove message
     *
     * @param \AppBundle\Entity\Message_section $message
     */
    public function removeMessage(\AppBundle\Entity\Message_section $message)
    {
        $this->messages->removeElement($message);
    }

    /**
     * Get messages
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getMessages()
    {
        return $this->messages;
    }

    /**
     * Set sommeCoeffs
     *
     * @param integer $sommeCoeffs
     *
     * @return Section
     */
    public function setSommeCoeffs($sommeCoeffs)
    {
        $this->sommeCoeffs = $sommeCoeffs;

        return $this;
    }

    /**
     * Get sommeCoeffs
     *
     * @return integer
     */
    public function getSommeCoeffs()
    {
        return $this->sommeCoeffs;
    }
}
