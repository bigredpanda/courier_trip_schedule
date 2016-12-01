<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="RegionRepository")
 * @ORM\Table(name="region")
 */
class Region
{
    /**
     * @var integer
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * Название региона
     * @ORM\Column(name="name", type="string", length=32)
     */
    protected $name;

    /**
     * Длительность поездки
     * @ORM\Column(name="trip_duration", type="smallint")
     */
    protected $tripDuration;

    /**
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\Schedule", mappedBy="region")
     */
    protected $schedule;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->schedule = new \Doctrine\Common\Collections\ArrayCollection();
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
     *
     * @return Region
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
     * Set tripDuration
     *
     * @param integer $tripDuration
     *
     * @return Region
     */
    public function setTripDuration($tripDuration)
    {
        $this->tripDuration = $tripDuration;

        return $this;
    }

    /**
     * Get tripDuration
     *
     * @return integer
     */
    public function getTripDuration()
    {
        return $this->tripDuration;
    }

    /**
     * Add schedule
     *
     * @param \AppBundle\Entity\Schedule $schedule
     *
     * @return Region
     */
    public function addSchedule(\AppBundle\Entity\Schedule $schedule)
    {
        $this->schedule[] = $schedule;

        return $this;
    }

    /**
     * Remove schedule
     *
     * @param \AppBundle\Entity\Schedule $schedule
     */
    public function removeSchedule(\AppBundle\Entity\Schedule $schedule)
    {
        $this->schedule->removeElement($schedule);
    }

    /**
     * Get schedule
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getSchedule()
    {
        return $this->schedule;
    }
}
