<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="ScheduleRepository")
 * @ORM\Table(name="schedule")
 */
class Schedule
{
    /**
     * @var integer
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * Регион
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Region", inversedBy="schedule")
     * @ORM\JoinColumn(name="region_id", referencedColumnName="id", onDelete="CASCADE")
     */
    protected $region;

    /**
     * Курьер
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Courier", inversedBy="schedule")
     * @ORM\JoinColumn(name="courier_id", referencedColumnName="id", onDelete="CASCADE")
     */
    protected $courier;

    /**
     * Дата выезда
     * @ORM\Column(name="departure_date", type="date")
     */
    protected $departureDate;

    /**
     * Дата прибытия
     * @ORM\Column(name="arrival_date", type="date")
     */
    protected $arrivalDate;


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
     * Set departureDate
     *
     * @param \DateTime $departureDate
     *
     * @return Schedule
     */
    public function setDepartureDate($departureDate)
    {
        $this->departureDate = $departureDate;

        return $this;
    }

    /**
     * Get departureDate
     *
     * @return \DateTime
     */
    public function getDepartureDate()
    {
        return $this->departureDate;
    }

    /**
     * Set arrivalDate
     *
     * @param \DateTime $arrivalDate
     *
     * @return Schedule
     */
    public function setArrivalDate($arrivalDate)
    {
        $this->arrivalDate = $arrivalDate;

        return $this;
    }

    /**
     * Get arrivalDate
     *
     * @return \DateTime
     */
    public function getArrivalDate()
    {
        return $this->arrivalDate;
    }

    /**
     * Set region
     *
     * @param \AppBundle\Entity\Region $region
     *
     * @return Schedule
     */
    public function setRegion(\AppBundle\Entity\Region $region = null)
    {
        $this->region = $region;

        return $this;
    }

    /**
     * Get region
     *
     * @return \AppBundle\Entity\Region
     */
    public function getRegion()
    {
        return $this->region;
    }

    /**
     * Set courier
     *
     * @param \AppBundle\Entity\Courier $courier
     *
     * @return Schedule
     */
    public function setCourier(\AppBundle\Entity\Courier $courier = null)
    {
        $this->courier = $courier;

        return $this;
    }

    /**
     * Get courier
     *
     * @return \AppBundle\Entity\Courier
     */
    public function getCourier()
    {
        return $this->courier;
    }
}
