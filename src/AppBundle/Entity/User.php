<?php

namespace AppBundle\Entity;

use FOS\UserBundle\Entity\User as BaseUser;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity
 * @ORM\Table(name="fos_user")
 */
class User extends BaseUser
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /** @ORM\Column(name="facebook_id", type="string", length=255, nullable=true) */
    protected $facebook_id;

    /** @ORM\Column(name="facebook_access_token", type="string", length=255, nullable=true) */
    protected $facebook_access_token;

    /**
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\Filter", mappedBy="user")
     **/
    private $filters;

    /**
     * @Assert\NotBlank()
     */
    protected $email;

    /** @ORM\Column(name="available_filter_count", type="integer") */
    protected $availableFilterCount = 2;

    /**
     * @var string
     */
    private $tempPlainPassword;

    public function __construct()
    {
        parent::__construct();
        $this->filters = new ArrayCollection();
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
     * Add filters
     *
     * @param \AppBundle\Entity\Filter $filters
     * @return User
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

    /**
     * Set facebook_id
     *
     * @param string $facebookId
     * @return User
     */
    public function setFacebookId($facebookId)
    {
        $this->facebook_id = $facebookId;

        return $this;
    }

    /**
     * Get facebook_id
     *
     * @return string 
     */
    public function getFacebookId()
    {
        return $this->facebook_id;
    }

    /**
     * Set facebook_access_token
     *
     * @param string $facebookAccessToken
     * @return User
     */
    public function setFacebookAccessToken($facebookAccessToken)
    {
        $this->facebook_access_token = $facebookAccessToken;

        return $this;
    }

    /**
     * Get facebook_access_token
     *
     * @return string 
     */
    public function getFacebookAccessToken()
    {
        return $this->facebook_access_token;
    }

    /**
     * @return string
     */
    public function getTempPlainPassword()
    {
        return $this->tempPlainPassword;
    }

    /**
     * @param string $tempPlainPassword
     */
    public function setTempPlainPassword($tempPlainPassword)
    {
        $this->tempPlainPassword = $tempPlainPassword;
    }

    /**
     * @return int
     */
    public function getAvailableFilterCount(): int
    {
        return $this->availableFilterCount;
    }

    /**
     * @param int $availableFilterCount
     */
    public function setAvailableFilterCount(int $availableFilterCount)
    {
        $this->availableFilterCount = $availableFilterCount;
    }
}
