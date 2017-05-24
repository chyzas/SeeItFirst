<?php

namespace AppBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="websites")
 */
class Site
{
    const SITE_SKELBIU = 'skelbiu.lt';
    const SITE_AUTOPLIUS = 'autoplius.lt';
    const SITE_ARUODAS = 'aruodas.lt';

    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=255, nullable=true)
     */
    protected $name;

    /**
     * @var string
     *
     * @ORM\Column(name="site_url", type="string", length=255, nullable=true)
     */
    protected $siteUrl;

    /**
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\Filter", mappedBy="site")
     **/
    protected $filters;

    public function __construct()
    {
        $this->filters = new ArrayCollection();
    }

    public function __toString() {
        return $this->getName();
    }

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
     * Set name
     *
     * @param string $name
     * @return Site
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
     * Set siteUrl
     *
     * @param string $siteUrl
     * @return Site
     */
    public function setSiteUrl($siteUrl)
    {
        $this->siteUrl = $siteUrl;

        return $this;
    }

    /**
     * Get siteUrl
     *
     * @return string 
     */
    public function getSiteUrl()
    {
        return $this->siteUrl;
    }

    /**
     * Add filters
     *
     * @param \AppBundle\Entity\Filter $filters
     * @return Site
     */
    public function addFilter(\AppBundle\Entity\Filter $filters)
    {
        $this->filters[] = $filters;

        return $this;
    }

    /**
     * Remove filters
     *
     * @param \AppBundle\Entity\Filter $filters
     */
    public function removeFilter(\AppBundle\Entity\Filter $filters)
    {
        $this->filters->removeElement($filters);
    }

    /**
     * Get filters
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getFilters()
    {
        return $this->filters;
    }
}
