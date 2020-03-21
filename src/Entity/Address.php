<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ORM\Embeddable
 */
class Address
{
    /**
     * @ORM\Column(type="string")
     *
     * @Groups({
     *     "user_read",
     *     "user_write",
     *     "ticket_write",
     * })
     */
    private $street;

    /**
     * @ORM\Column(type="string")
     *
     * @Groups({
     *     "user_read",
     *     "user_write",
     *     "ticket_write",
     * })
     */
    private $houseNumber;

    /**
     * @ORM\Column(type="string")
     *
     * @Groups({
     *     "user_read",
     *     "user_write",
     *     "ticket_write",
     * })
     */
    private $postalCode;

    /**
     * @ORM\Column(type="string")
     *
     * @Groups({
     *     "user_read",
     *     "user_write",
     *     "ticket_write",
     * })
     */
    private $city;

    /**
     * @ORM\Column(type="decimal", precision=18, scale=12, nullable=true)
     *
     * @Groups({
     *     "user_read",
     *     "user_write",
     *     "ticket_read",
     *     "ticket_write",
     * })
     */
    private $latitude;

    /**
     * @ORM\Column(type="decimal", precision=18, scale=12, nullable=true)
     *
     * @Groups({
     *     "user_read",
     *     "user_write",
     *     "ticket_read",
     *     "ticket_write",
     * })
     */
    private $longitude;

    /**
     * Address constructor.
     *
     * @param null $street
     * @param null $houseNumber
     * @param null $city
     * @param null $postalCode
     */
    public function __construct($street = null, $houseNumber = null, $city = null, $postalCode = null)
    {
        $this->setStreet($street);
        $this->setHouseNumber($houseNumber);
        $this->setCity($city);
        $this->setPostalCode($postalCode);
    }

    /**
     * @return mixed
     */
    public function getStreet()
    {
        return $this->street;
    }

    /**
     * @param mixed $street
     * @return Address
     */
    public function setStreet($street)
    {
        $this->street = $street;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getHouseNumber()
    {
        return $this->houseNumber;
    }

    /**
     * @param mixed $houseNumber
     * @return Address
     */
    public function setHouseNumber($houseNumber)
    {
        $this->houseNumber = $houseNumber;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getPostalCode()
    {
        return $this->postalCode;
    }

    /**
     * @param mixed $postalCode
     * @return Address
     */
    public function setPostalCode($postalCode)
    {
        $this->postalCode = $postalCode;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getCity()
    {
        return $this->city;
    }

    /**
     * @param mixed $city
     * @return Address
     */
    public function setCity($city)
    {
        $this->city = $city;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getLatitude()
    {
        return $this->latitude;
    }

    /**
     * @param mixed $latitude
     * @return Address
     */
    public function setLatitude($latitude)
    {
        $this->latitude = $latitude;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getLongitude()
    {
        return $this->longitude;
    }

    /**
     * @param mixed $longitude
     * @return Address
     */
    public function setLongitude($longitude)
    {
        $this->longitude = $longitude;

        return $this;
    }
}