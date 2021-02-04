<?php declare(strict_types=1);

namespace UdgStorefinder\Entity;

use Exception;
use Shopware\Core\Framework\DataAbstractionLayer\Entity;
use Shopware\Core\Framework\DataAbstractionLayer\EntityIdTrait;
use Shopware\Core\System\Country\CountryEntity;

/**
 * Class UdgStorefinderEntity
 * @package UdgStorefinder\Entity
 *
 * @method getCompany()
 * @method getLocation()
 * @method getStreet()
 * @method getPhone()
 * @method getEmail()
 * @method getWeb()
 * @method getUdgStorefinderId()
 * @method getUdgStorefinder()
 */
class UdgStorefinderEntity extends Entity
{
    use EntityIdTrait;

    /**
     * @var string
     */
    protected $distrotype;

    /**
     * @var string
     */
    protected $productline;

    /**
     * @var string
     */
    protected $phoneRaw;

    /**
     * @var float
     */
    protected $latitude;

    /**
     * @var float
     */
    protected $longitude;

    /**
     * @var bool
     */
    protected $active;

    /**
     * @var string
     */
    protected $countryId;

    /**
     * @var CountryEntity
     */
    protected $country;


    /**
     * @return string
     */
    public function getCountryId(): string
    {
        return $this->countryId;
    }

    /**
     * @param string $countryId
     */
    public function setCountryId(string $countryId): void
    {
        $this->countryId = $countryId;
    }

    /**
     * @return string
     */
    public function getDistrotype(): string
    {
        return $this->distrotype;
    }

    /**
     * @param string $distrotype
     */
    public function setDistrotype(string $distrotype): void
    {
        $this->distrotype = $distrotype;
    }

    /**
     * @return string
     */
    public function getProductline(): string
    {
        return $this->productline;
    }

    /**
     * @param string $productline
     */
    public function setProductline(string $productline): void
    {
        $this->productline = $productline;
    }

    /**
     * @return string
     */
    public function getPhoneRaw(): string
    {
        return $this->phoneRaw;
    }

    /**
     * @param string $phoneRaw
     */
    public function setPhoneRaw(string $phoneRaw): void
    {
        $this->phoneRaw = $phoneRaw;
    }

    /**
     * @return float
     */
    public function getLatitude(): float
    {
        return $this->latitude;
    }

    /**
     * @param float $latitude
     */
    public function setLatitude(float $latitude): void
    {
        $this->latitude = $latitude;
    }

    /**
     * @return float
     */
    public function getLongitude(): float
    {
        return $this->longitude;
    }

    /**
     * @param float $longitude
     */
    public function setLongitude(float $longitude): void
    {
        $this->longitude = $longitude;
    }

    /**
     * @return bool
     */
    public function getActive(): bool
    {
        return $this->active;
    }

    /**
     * @param bool $active
     */
    public function setActive(bool $active): void
    {
        $this->active = $active;
    }

    /**
     * @return CountryEntity
     */
    public function getCountry(): ?CountryEntity
    {
        return $this->country;
    }

    /**
     * @param CountryEntity $country
     */
    public function setCountry(CountryEntity $country): void
    {
        $this->country = $country;
    }


    /**
     * @param string $methodName
     * @return string|null
     */
    public function __call(string $methodName, array $arguments): ?string
    {
        try {
            $property = lcfirst(substr($methodName, strlen('get')));

            if (empty($property) === false) {
                return $this->getTranslation($property);
            }
        } catch (Exception $e) {
            // ignore errors
        }

        return null;
    }
}
