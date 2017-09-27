<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="AppBundle\Repository\ResultsRepository")
 * @ORM\Table(name="results")
 */
class Results
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @var bool
     *
     * @ORM\Column(name="is_new", type="boolean", nullable=true)
     */
    protected $isNew;

    /**
     * @var int
     *
     * @ORM\Column(name="price", type="string", nullable=true)
     */
    protected $price;

    /**
     * @var string
     *
     * @ORM\Column(name="url", type="string", unique=true)
     */
    protected $url;

    /**
     * @var string
     *
     * @ORM\Column(name="title", type="text", nullable=true)
     */
    protected $title;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="added_on", type="datetime", nullable=true)
     */
    protected $addedOn;

    /**
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Filter", inversedBy="results", cascade={"persist"})
     * @ORM\JoinColumn(name="filter_id", referencedColumnName="id", onDelete="SET NULL", nullable=true)
     **/
    private $filter;

    /**
     * @var string
     *
     * @ORM\Column(name="item_id", type="text")
     */
    private $itemId;

    /**
     * @var string
     *
     * @ORM\Column(name="details", type="text", nullable=true)
     */
    private $details;

    /**
     * @var string
     *
     * @ORM\Column(name="image", type="text", nullable=true)
     */
    private $image;

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
     * Set isNew
     *
     * @param boolean $isNew
     * @return Results
     */
    public function setIsNew($isNew)
    {
        $this->isNew = $isNew;

        return $this;
    }

    /**
     * Get isNew
     *
     * @return boolean 
     */
    public function getIsNew()
    {
        return $this->isNew;
    }

    /**
     * Set price
     *
     * @param float $price
     * @return Results
     */
    public function setPrice($price)
    {
        $this->price = $price;

        return $this;
    }

    /**
     * Get price
     *
     * @return float 
     */
    public function getPrice()
    {
        return $this->price;
    }

    /**
     * Set url
     *
     * @param string $url
     * @return Results
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
     * Set title
     *
     * @param string $title
     * @return Results
     */
    public function setTitle($title)
    {
        $this->title = $title;

        return $this;
    }

    /**
     * Get title
     *
     * @return string 
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * Set addedOn
     *
     * @param \DateTime $addedOn
     * @return Results
     */
    public function setAddedOn($addedOn)
    {
        $this->addedOn = $addedOn;

        return $this;
    }

    /**
     * Get addedOn
     *
     * @return \DateTime 
     */
    public function getAddedOn()
    {
        return $this->addedOn;
    }

    /**
     * Set filter
     *
     * @param Filter $filter
     * @return Results
     */
    public function setFilter(Filter $filter = null)
    {
        $this->filter = $filter;

        return $this;
    }

    /**
     * Get filter
     *
     * @return Filter
     */
    public function getFilter()
    {
        return $this->filter;
    }

    /**
     * @return string
     */
    public function getItemId(): string
    {
        return $this->itemId;
    }

    /**
     * @param string $itemId
     */
    public function setItemId(string $itemId)
    {
        $this->itemId = $itemId;
    }

    /**
     * @return string
     */
    public function getDetails(): string
    {
        return $this->details;
    }

    /**
     * @param string $details
     */
    public function setDetails(string $details)
    {
        $this->details = $details;
    }

    /**
     * @return string
     */
    public function getImage(): string
    {
        return $this->image;
    }

    /**
     * @param string $image
     */
    public function setImage(string $image)
    {
        $this->image = $image;
    }
}
