<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="examen_etudiant")
 */
class Examen_etudiant
{
  /**
   * @ORM\Column(name="id", type="integer")
   * @ORM\Id
   * @ORM\GeneratedValue(strategy="AUTO")
   */
  private $id;

  /**
   * @ORM\Column(name="note", type="float")
   */
  private $note;

  /**
   * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Examen")
   * @ORM\JoinColumn(nullable=false)
   */
  private $examen;

  /**
   * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Etudiant", inversedBy="notes")
   * @ORM\JoinColumn(nullable=false)
   */
  private $etudiant;
  

    /**
     * Get id
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set note
     *
     * @param float $note
     *
     * @return Examen_etudiant
     */
    public function setNote($note)
    {
        $this->note = $note;

        return $this;
    }

    /**
     * Get note
     *
     * @return float
     */
    public function getNote()
    {
        return $this->note;
    }

    /**
     * Set examen
     *
     * @param \AppBundle\Entity\Examen $examen
     *
     * @return Examen_etudiant
     */
    public function setExamen(\AppBundle\Entity\Examen $examen)
    {
        $this->examen = $examen;

        return $this;
    }

    /**
     * Get examen
     *
     * @return \AppBundle\Entity\Examen
     */
    public function getExamen()
    {
        return $this->examen;
    }

    /**
     * Set etudiant
     *
     * @param \AppBundle\Entity\Etudiant $etudiant
     *
     * @return Examen_etudiant
     */
    public function setEtudiant(\AppBundle\Entity\Etudiant $etudiant)
    {
        $this->etudiant = $etudiant;

        return $this;
    }

    /**
     * Get etudiant
     *
     * @return \AppBundle\Entity\Etudiant
     */
    public function getEtudiant()
    {
        return $this->etudiant;
    }
}
