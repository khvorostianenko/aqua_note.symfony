<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="genus")
 */
class Genus
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     * @ORM\Column(type="integer")
     */
    private $id;
    
    /**
     * @ORM\Column(type="string")
     */
    private $name;
    
    /**
     * @ORM\Column(type="string")
     */
    private $subFamily;
    
    /**
     * @ORM\Column(type="integer")
     */
    private $speciesCount;
    
    /**
     * @ORM\Column(type="string")
     */
    private $funFact;
    
    /**
     * @return mixed
     */
    public function getSubFamily()
    {
        return $this->subFamily;
    }
    
    /**
     * @return mixed
     */
    public function getSpeciesCount()
    {
        return $this->speciesCount;
    }
    
    /**
     * @return mixed
     */
    public function getFunFact()
    {
        return $this->funFact;
    }
    
    /**
     * @param mixed $subFamily
     */
    public function setSubFamily($subFamily)
    {
        $this->subFamily = $subFamily;
    }
    
    /**
     * @param mixed $speciesCount
     */
    public function setSpeciesCount($speciesCount)
    {
        $this->speciesCount = $speciesCount;
    }
    
    /**
     * @param mixed $funFact
     */
    public function setFunFact($funFact)
    {
        $this->funFact = $funFact;
    }
    
    /**
     * @return mixed
     */
    public function getName()
    {
        return $this->name;
    }
    
    /**
     * @param mixed $name
     */
    public function setName($name)
    {
        $this->name = $name;
    }
}