<?php declare(strict_types = 1);

namespace UdgStorefinder\Entity\Aggregate\UdgStorefinderTranslation;

use UdgStorefinder\Entity\UdgStorefinderEntity;
use Shopware\Core\Framework\DataAbstractionLayer\TranslationEntity;

/**
 * Class UdgStorefinderTranslationEntity
 * @package UdgStorefinder\Entity\Aggregate\UdgStorefinderTranslation
 */
class UdgStorefinderTranslationEntity extends TranslationEntity
{
    /**
     * @var string
     */
    protected $udgStorefinderId;

    /**
     * @var UdgStorefinderEntity
     */
    protected $udgStorefinder;

    /**
     * @var string|null
     */
    protected $company;

    /**
     * @var string|null
     */
    protected $location;

    /**
     * @var string|null
     */
    protected $street;

    /**
     * @var string|null
     */
    protected $phone;

    /**
     * @var string|null
     */
    protected $email;

    /**
     * @var string|null
     */
    protected $web;


    /**
     * COLUMNS
     *
     * @return string|null
     */
    public function getCompany(): ?string
    {
        return $this->company;
    }

    /**
     * @param string|null $company
     */
    public function setCompany(?string $company): void
    {
        $this->company = $company;
    }

    /**
     * @return string|null
     */
    public function getLocation(): ?string
    {
        return $this->location;
    }

    /**
     * @param string|null $location
     */
    public function setLocation(?string $location): void
    {
        $this->location = $location;
    }

    /**
     * @return string|null
     */
    public function getStreet(): ?string
    {
        return $this->street;
    }

    /**
     * @param string|null $street
     */
    public function setStreet(?string $street): void
    {
        $this->street = $street;
    }

    /**
     * @return string|null
     */
    public function getPhone(): ?string
    {
        return $this->phone;
    }

    /**
     * @param string|null $phone
     */
    public function setPhone(?string $phone): void
    {
        $this->phone = $phone;
    }

    /**
     * @return string|null
     */
    public function getEmail(): ?string
    {
        return $this->email;
    }

    /**
     * @param string|null $email
     */
    public function setEmail(?string $email): void
    {
        $this->email = $email;
    }

    /**
     * @return string|null
     */
    public function getWeb(): ?string
    {
        return $this->web;
    }

    /**
     * @param string|null $web
     */
    public function setWeb(?string $web): void
    {
        $this->web = $web;
    }


    /**
     * ASSOCIATIONS
     *
     * @return string
     */
    public function getUdgStorefinderId(): string
    {
        return $this->udgStorefinderId;
    }

    /**
     * @param string $udgStorefinderId
     */
    public function setUdgStorefinderId(string $udgStorefinderId): void
    {
        $this->udgStorefinderId = $udgStorefinderId;
    }

    /**
     * @return UdgStorefinderEntity|null
     */
    public function getUdgStorefinder(): ?UdgStorefinderEntity
    {
        return $this->udgStorefinder;
    }

    /**
     * @param UdgStorefinderEntity|null $udgStorefinder
     */
    public function setUdgStorefinder(?UdgStorefinderEntity $udgStorefinder): void
    {
        $this->udgStorefinder = $udgStorefinder;
    }
}
