<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Etudiant
 *
 * @ORM\Table(name="etudiant")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\EtudiantRepository")
 */
class Etudiant
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
     * @ORM\Column(name="matricule", type="string", length=255)
     */
    private $matricule;

    /**
    * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Groupe", inversedBy="etudiants")
    * @ORM\JoinColumn(nullable=true, onDelete="SET NULL")
    */
    private $groupe;

    /**
    * @ORM\ManyToOne(targetEntity="AppBundle\Entity\User", cascade={"remove"})
    * @ORM\JoinColumn(nullable=true)
    */
    private $user;

    /**
    * @ORM\OneToMany(targetEntity="AppBundle\Entity\Examen_etudiant", mappedBy="etudiant", cascade={"remove"})
    */
    private $notes;

    /**
    * @ORM\OneToMany(targetEntity="AppBundle\Entity\Message_prive", mappedBy="etudiant", cascade={"remove"})
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
     * Set matricule
     *
     * @param string $matricule
     *
     * @return Etudiant
     */
    public function setMatricule($matricule)
    {
        $this->matricule = $matricule;

        return $this;
    }

    /**
     * Get matricule
     *
     * @return string
     */
    public function getMatricule()
    {
        return $this->matricule;
    }

    /**
     * Set groupe
     *
     * @param \AppBundle\Entity\Groupe $groupe
     *
     * @return Etudiant
     */
    public function setGroupe(\AppBundle\Entity\Groupe $groupe = null)
    {
        $this->groupe = $groupe;

        return $this;
    }

    /**
     * Get groupe
     *
     * @return \AppBundle\Entity\Groupe
     */
    public function getGroupe()
    {
        return $this->groupe;
    }

    /**
     * Set user
     *
     * @param \AppBundle\Entity\User $user
     *
     * @return Etudiant
     */
    public function setUser(\AppBundle\Entity\User $user = null)
    {
        $this->user = $user;

        return $this;
    }

    /**
     * Get user
     *
     * @return \AppBundle\Entity\User
     */
    public function getUser()
    {
        return $this->user;
    }
    /**
     * Constructor
     */
    public function __construct()
    {
    }

    /**
     * Add note
     *
     * @param \AppBundle\Entity\Examen_etudiant $note
     *
     * @return Etudiant
     */
    public function addNote(\AppBundle\Entity\Examen_etudiant $note)
    {
        $this->notes[] = $note;

        return $this;
    }

    /**
     * Remove note
     *
     * @param \AppBundle\Entity\Examen_etudiant $note
     */
    public function removeNote(\AppBundle\Entity\Examen_etudiant $note)
    {
        $this->notes->removeElement($note);
    }

    /**
     * Get notes
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getNotes()
    {
        return $this->notes;
    }


    /**
     * Get moyenne
     *
     * @return float
     */
    public function getMoyenne()
    {
        if ($this->getGroupe()->getSection()->getSommeCoeffs()==null)
            return -1;

        $somme=0;

        foreach ($this->getNotes() as $note) {
            if ($note->getNote()==NULL)
                return -1;
            else
                $somme = $somme + $note->getNote()*$note->getExamen()->getCoefficient();
        }

        $note = $somme / $this->getGroupe()->getSection()->getSommeCoeffs();

        return $note;
    }


    /**
     * Add message
     *
     * @param \AppBundle\Entity\Message_prive $message
     *
     * @return Etudiant
     */
    public function addMessage(\AppBundle\Entity\Message_prive $message)
    {
        $this->messages[] = $message;

        return $this;
    }

    /**
     * Remove message
     *
     * @param \AppBundle\Entity\Message_prive $message
     */
    public function removeMessage(\AppBundle\Entity\Message_prive $message)
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
}
