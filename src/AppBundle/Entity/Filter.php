<?php

namespace AppBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Context\ExecutionContextInterface;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * @Assert\Callback(methods={"checkFilter"})
 * @ORM\Entity
 * @ORM\Table(name="filter")
 * @UniqueEntity(fields={"site", "url"})})
 */
class Filter
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\User", inversedBy="filters")
     * @ORM\JoinColumn(name="user_id", referencedColumnName="id")
     **/
    protected $user;

    /**
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Site", inversedBy="filters")
     * @ORM\JoinColumn(name="site_id", referencedColumnName="id")
     * @Assert\NotBlank()
     **/
    protected $site;

    /**
     * @var string
     *
     * @ORM\Column(name="url", type="string", length=1000, nullable=false)
     * @Assert\NotBlank()
     */
    protected $url;

    /**
     * @var string
     *
     * @ORM\Column(name="filter_name", type="string", nullable=false)
     * @Assert\NotBlank()
     */
    protected $filterName;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="created_at", type="datetime", nullable=false)
     */
    protected $createdAt;

    /**
     * @var bool
     *
     * @ORM\Column(name="active", type="boolean", nullable=false)
     */
    protected $active;

    /**
     * @var string
     *
     * @ORM\Column(name="token", type="string", nullable=false)
     */
    protected $token;

    /**
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\Results", mappedBy="filter", cascade={"remove"})
     **/
    protected $results;

    public function __construct()
    {
        $this->results = new ArrayCollection();
        $this->setCreatedAt(new \DateTime());
        $this->setActive(false);
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
     * Set url
     *
     * @param string $url
     * @return Filter
     */
    public function setUrl($url)
    {
        $this->url = $url;

        return $this;
    }

    /**
     * Get url
     *
     * @return string 
     */
    public function getUrl()
    {
        return $this->url;
    }

    /**
     * Set user
     *
     * @param \AppBundle\Entity\User $user
     * @return Filter
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
     * Set site
     *
     * @param \AppBundle\Entity\Site $site
     * @return Filter
     */
    public function setSite(\AppBundle\Entity\Site $site = null)
    {
        $this->site = $site;

        return $this;
    }

    /**
     * Get site
     *
     * @return \AppBundle\Entity\Site 
     */
    public function getSite()
    {
        return $this->site;
    }

    /**
     * Add results
     *
     * @param \AppBundle\Entity\Results $results
     * @return Filter
     */
    public function addResult(\AppBundle\Entity\Results $results)
    {
        $this->results[] = $results;

        return $this;
    }

    /**
     * Remove results
     *
     * @param \AppBundle\Entity\Results $results
     */
    public function removeResult(\AppBundle\Entity\Results $results)
    {
        $this->results->removeElement($results);
    }

    /**
     * Get results
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getResults()
    {
        return $this->results;
    }

    public function checkFilter(ExecutionContextInterface $contextInterface)
    {
        $site = $this->getSite()->getSiteUrl();
        $filterUrl = $this->getUrl();

        if (!isset(parse_url($filterUrl)['host']) || parse_url($site)['host'] !== parse_url($filterUrl)['host']) {
            $contextInterface
                ->buildViolation('Please enter correct url filter.')
                ->atPath('url')
                ->addViolation();
        }
    }

    /**
     * Set filterName
     *
     * @param string $filterName
     * @return Filter
     */
    public function setFilterName($filterName)
    {
        $this->filterName = $filterName;

        return $this;
    }

    /**
     * Get filterName
     *
     * @return string 
     */
    public function getFilterName()
    {
        return $this->filterName;
    }

    /**
     * @return \DateTime
     */
    public function getCreatedAt()
    {
        return $this->createdAt;
    }

    /**
     * @param \DateTime $createdAt
     */
    public function setCreatedAt($createdAt)
    {
        $this->createdAt = $createdAt;
    }

    /**
     * @return bool
     */
    public function isActive()
    {
        return $this->active;
    }

    /**
     * @param bool $active
     */
    public function setActive($active)
    {
        $this->active = $active;
    }

    /**
     * @return string
     */
    public function getToken(): string
    {
        return $this->token;
    }

    /**
     * @param string $token
     */
    public function setToken(string $token)
    {
        $this->token = $token;
    }
}
