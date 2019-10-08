<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Foyer
 *
 * @ORM\Table(name="foyer")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\FoyerRepository")
 */
class Foyer
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
     * @ORM\Column(name="titre", type="string", length=255)
     */
    private $titre;
    /**
     * @var string
     *
     * @ORM\Column(name="ville", type="string", length=255)
     */
    private $ville;
    /**
     * @var string
     *
     * @ORM\Column(name="adresse", type="string", length=255)
     */
    private $adresse;
    /**
     * @var int
     *
     * @ORM\Column(name="nbChmbr", type="integer")
     */
    private $nbChmbr;

    /**
     * @var int
     *
     * @ORM\Column(name="prixIndiv", type="integer")
     */
    private $prixIndiv;

    /**
     * @var int
     *
     * @ORM\Column(name="prix2", type="integer")
     */
    private $prix2;

    /**
     * @var string
     *
     * @ORM\Column(name="prix3", type="integer")
     */
    private $prix3;

    /**
     *
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\User")
     */
    private $membre;

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
     * Set nbChmbr
     *
     * @param integer $nbChmbr
     *
     * @return Foyer
     */
    public function setNbChmbr($nbChmbr)
    {
        $this->nbChmbr = $nbChmbr;

        return $this;
    }

    /**
     * Get nbChmbr
     *
     * @return int
     */
    public function getNbChmbr()
    {
        return $this->nbChmbr;
    }

    /**
     * Set prixIndiv
     *
     * @param integer $prixIndiv
     *
     * @return Foyer
     */
    public function setPrixIndiv($prixIndiv)
    {
        $this->prixIndiv = $prixIndiv;

        return $this;
    }

    /**
     * Get prixIndiv
     *
     * @return int
     */
    public function getPrixIndiv()
    {
        return $this->prixIndiv;
    }

    /**
     * Set prix2
     *
     * @param integer $prix2
     *
     * @return Foyer
     */
    public function setPrix2($prix2)
    {
        $this->prix2 = $prix2;

        return $this;
    }

    /**
     * Get prix2
     *
     * @return int
     */
    public function getPrix2()
    {
        return $this->prix2;
    }

    /**
     * Set prix3
     *
     * @param string $prix3
     *
     * @return Foyer
     */
    public function setPrix3($prix3)
    {
        $this->prix3 = $prix3;

        return $this;
    }

    /**
     * Get prix3
     *
     * @return string
     */
    public function getPrix3()
    {
        return $this->prix3;
    }

    /**
     * @return mixed
     */
    public function getMembre()
    {
        return $this->membre;
    }

    /**
     * @param mixed $membre
     */
    public function setMembre($membre)
    {
        $this->membre = $membre;
    }

    /**
     * @return string
     */
    public function getTitre()
    {
        return $this->titre;
    }

    /**
     * @param string $titre
     */
    public function setTitre($titre)
    {
        $this->titre = $titre;
    }

    /**
     * @return string
     */
    public function getVille()
    {
        return $this->ville;
    }

    /**
     * @param string $ville
     */
    public function setVille($ville)
    {
        $this->ville = $ville;
    }

    /**
     * @return string
     */
    public function getAdresse()
    {
        return $this->adresse;
    }

    /**
     * @param string $adresse
     */
    public function setAdresse($adresse)
    {
        $this->adresse = $adresse;
    }
}

