<?php declare(strict_types=1);

namespace UdgStorefinder\Service;

use Exception;
use Shopware\Core\Framework\Context;
use Shopware\Core\Framework\DataAbstractionLayer\Search\EntitySearchResult;
use Shopware\Core\Framework\DataAbstractionLayer\Search\Filter\ContainsFilter;
use Shopware\Core\Framework\DataAbstractionLayer\Search\Filter\EqualsFilter;
use Shopware\Core\Framework\DataAbstractionLayer\Search\Filter\MultiFilter;
use Shopware\Core\Framework\DataAbstractionLayer\Search\Filter\RangeFilter;
use Shopware\Core\Framework\DataAbstractionLayer\Search\Sorting\FieldSorting;
use Shopware\Core\Framework\Uuid\Exception\InvalidUuidException;
use Shopware\Core\Framework\Uuid\Exception\InvalidUuidLengthException;
use Shopware\Core\System\Country\CountryEntity;
use Shopware\Core\System\SalesChannel\SalesChannelContext;
use Shopware\Core\Framework\DataAbstractionLayer\EntityRepositoryInterface AS EntityRepository;
use Shopware\Core\Framework\DataAbstractionLayer\Search\Criteria;
use Shopware\Core\System\Language\LanguageEntity;
use Symfony\Component\HttpFoundation\Request;
use UdgStorefinder\Entity\UdgStorefinderCollection;
use UdgStorefinder\Entity\UdgStorefinderEntity;

/**
 * Class LocationService
 * @package UdgStorefinder\Service
 */
class LocationService
{
    /**
     * const
     */
    public const ISO_DE = 'DE';
    public const ISO_EN_GB = 'en-GB';
    public const ISO_DE_DE = 'de-DE';
    public const LONG_LAT_RANGE = array(
        'de-DE' => array(
            'longitudeMin' => 1.5139158814895293,
            'longitudeMax' => 19.42602552471043,
            'latitudeMin' => 48.16364275597455,
            'latitudeMax' => 53.48586649332204
        ),
        'en-GB' => array(
            'longitudeMin' => -143.6484396457672,
            'longitudeMax' => 142.9453146457672,
            'latitudeMin' => -32.645545264652775,
            'latitudeMax' => 70.3384614099008
        )
    );


    /**
     * @var EntityRepository
     */
    private $storefinderRepository;

    /**
     * @var EntityRepository
     */
    private $languageRepository;


    /**
     * LocationService constructor.
     * @param EntityRepository $storefinderRepository
     * @param EntityRepository $languageRepository
     */
    public function __construct(EntityRepository $storefinderRepository, EntityRepository $languageRepository)
    {
        $this->setStorefinderRepository($storefinderRepository);
        $this->setLanguageRepository($languageRepository);
    }


    /**
     * @param SalesChannelContext $context
     * @return array
     */
    public function getStorefinderLocations(SalesChannelContext $context): array
    {
        $entities = $this->loadStorefinderLocations($context->getContext());
        $entities = $this->prepareStorefinderLocations($entities);

        $entitiesGroup = array();

        foreach ($entities as $entitieIndex => $entity) {
            if ($entity->getCountry() instanceof CountryEntity) {
                if (array_key_exists($entity->getCountryId(), $entitiesGroup) === false) {
                    $entitiesGroup[$entity->getCountryId()] = array(
                        'country' => $entity->getCountry(),
                        'locations' => array()
                    );
                }

                $entitiesGroup[$entity->getCountryId()]['locations'][] = $entity;
            }
        }

        return $entitiesGroup;
    }

    /**
     * @param SalesChannelContext $context
     * @return string
     */
    public function getStorefinderLocationsAsJson(SalesChannelContext $context): string
    {
        $entities = $this->loadStorefinderLocations($context->getContext());
        $entities = $this->prepareStorefinderLocations($entities);
        $entities = $this->prepareStorefinderLocationsForJson($entities);

        return json_encode($entities);
    }

    /**
     * @param SalesChannelContext $context
     * @param Request $request
     * @return string
     */
    public function getStorefinderLocationsBySearchAsJson(SalesChannelContext $context, Request $request): string
    {
        $entities = $this->loadStorefinderLocations($context->getContext(), $request);
        $entities = $this->prepareStorefinderLocations($entities);
        $entities = $this->prepareStorefinderLocationsForJson($entities);

        return json_encode($entities);
    }

    /**
     * @param SalesChannelContext $context
     * @param string $id
     * @return string
     */
    public function getStorefinderLocationsByIdAsJson(SalesChannelContext $context, string $id): string
    {
        $entities = $this->loadStorefinderLocation($context->getContext(), $id);
        $entities = $this->prepareStorefinderLocations($entities);
        $entities = $this->prepareStorefinderLocationsForJson($entities);

        return json_encode($entities);
    }


    /**
     * HELPERS
     *
     * @param array $entities
     * @return array
     */
    private function prepareStorefinderLocations(array $entities): array
    {
        $entities = $this->prepareStorefinderLocationsPhoneNumber($entities);

        return $entities;
    }

    /**
     * @param array $entities
     * @return array
     */
    private function prepareStorefinderLocationsPhoneNumber(array $entities): array
    {
        foreach ($entities as $entitieIndex => $entity) {
            /** @var UdgStorefinderEntity $entity */
            $phone = (string)$entity->getPhone();

            if ($phone === '') {
                $entity->setPhoneRaw('');

                continue;
            }

            if (!is_null($entity->getCountry()) && $entity->getCountry()->getIso() === self::ISO_DE && preg_match('/\+49/', $phone) === false) {
                $phone = '+49-' . $phone;
            }

            $phone = trim($phone);
            $phone = str_replace('(0)', '', $phone);
            $phone = str_replace('-', '', $phone);

            if ($phone[0] !== '+') {
                $phone = '+' . $phone;
            }

            $entity->setPhoneRaw($phone);
        }

        return $entities;
    }

    /**
     * @param array $entities
     * @return array
     */
    private function prepareStorefinderLocationsForJson(array $entities): array
    {
        $entitiesForJs = array();

        foreach ($entities as $entitieIndex => $entity) {
            /** @var UdgStorefinderEntity $entity */
            $entitiesTranslated = $entity->getTranslated();
            $entitiesTranslated['country'] = is_null($entity->getCountry()) ? '' : $entity->getCountry()->getName();
            $entitiesTranslated['phoneRaw'] = $entity->getPhoneRaw();
            $entitiesTranslated['company'] = str_replace('"', '&quot;', $entity->getTranslation('company'));
            $entitiesTranslated['id'] = $entity->getId();
            $entitiesTranslated['latitude'] = (string)$entity->getLatitude();
            $entitiesTranslated['longitude'] = (string)$entity->getLongitude();
            $entitiesTranslated['distrotype'] = $entity->getDistrotype();

            $entitiesForJs[] = $entitiesTranslated;
        }

        return $entitiesForJs;
    }

    /**
     * @param Context $context
     * @param Request|null $request
     * @return array
     */
    private function loadStorefinderLocations(Context $context, Request $request = null): array
    {
        try {
            $criteria = new Criteria([$context->getLanguageId()]);
            $criteria->addAssociation('locale');
            $languages = $this->getLanguageRepository()->search($criteria, $context);

            $criteria = new Criteria();
            $criteria->addAssociation('country');
            $criteria->addSorting(new FieldSorting('company'));
            $criteria->addFilter(new EqualsFilter('active', true));

            if ($request instanceof Request && empty($this->getSearchValueName($request)) === false) {
                $criteria->addFilter(new ContainsFilter('company', $this->getSearchValueName($request)));
            }

            if ($request instanceof Request && empty($this->getSearchValueLocation($request)) === false) {
                $criteria->addFilter(new MultiFilter(
                    MultiFilter::CONNECTION_OR,
                    [
                        new ContainsFilter('street', $this->getSearchValueLocation($request)),
                        new ContainsFilter('location', $this->getSearchValueLocation($request)),
                        new ContainsFilter('country.name', $this->getSearchValueLocation($request))
                    ]
                ));
            }

            /** @var UdgStorefinderCollection $entities */
            $entities = $this->getStorefinderRepository()->search($criteria, $context);

            return $entities->getElements();
        } catch (Exception $e) {
            return array();
        }
    }

    /**
     * @param Context $context
     * @param string $id
     * @return array
     */
    private function loadStorefinderLocation(Context $context, string $id): array
    {
        try {
            $criteria = new Criteria();
            $criteria->addAssociation('country');
            $criteria->addSorting(new FieldSorting('company'));
            $criteria->addFilter(new EqualsFilter('active', true));
            $criteria->addFilter(new EqualsFilter('id', $id));

            /** @var UdgStorefinderCollection $entities */
            $entities = $this->getStorefinderRepository()->search($criteria, $context);

            return $entities->getElements();
        } catch (Exception $e) {
            return array();
        }
    }


    /**
     * GET/SET
     *
     * @return EntityRepository
     */
    private function getStorefinderRepository(): EntityRepository
    {
        return $this->storefinderRepository;
    }

    /**
     * @param EntityRepository $storefinderRepository
     */
    private function setStorefinderRepository(EntityRepository $storefinderRepository): void
    {
        $this->storefinderRepository = $storefinderRepository;
    }

    /**
     * @return EntityRepository
     */
    private function getLanguageRepository(): EntityRepository
    {
        return $this->languageRepository;
    }

    /**
     * @param EntityRepository $languageRepository
     */
    private function setLanguageRepository(EntityRepository $languageRepository): void
    {
        $this->languageRepository = $languageRepository;
    }

    /**
     * @param Request $request
     * @return array
     */
    private function getSearchValues(Request $request): array
    {
        $params = array_merge(
            $request->query->all(),
            $request->request->all()
        );

        if (array_key_exists('storefinder-searchtype', $params) === false ||
            array_key_exists('storefinder-searchvalue', $params) === false) {
            return array();
        }

        if ($params['storefinder-searchtype'] !== 'name' && $params['storefinder-searchtype'] !== 'location') {
            return array();
        }

        return array($params['storefinder-searchtype'] => trim((string)$params['storefinder-searchvalue']));
    }

    /**
     * @param Request $request
     * @return string
     */
    private function getSearchValueName(Request $request): string
    {
        $searchValue = $this->getSearchValues($request);

        if (array_key_exists('name', $searchValue) === true) {
            return reset($searchValue);
        }

        return '';
    }

    /**
     * @param Request $request
     * @return string
     */
    private function getSearchValueLocation(Request $request): string
    {
        $searchValue = $this->getSearchValues($request);

        if (array_key_exists('location', $searchValue) === true) {
            return reset($searchValue);
        }

        return '';
    }

    /**
     * @param EntitySearchResult $languages
     * @param Request|null $request
     * @return array
     */
    private function getCoordinates(EntitySearchResult $languages, Request $request = null): array
    {
        if ($request instanceof Request) {
            $params = array_merge(
                $request->query->all(),
                $request->request->all()
            );

            $coords = array('longitudeMin', 'longitudeMax', 'latitudeMin', 'latitudeMax');
            $coordsFromRequest = array();

            foreach ($coords as $coord) {
                if (array_key_exists('latitudeMin', $params) === false) {
                    return $this->getCoordinatesDefault($languages);
                }

                $coordsFromRequest[$coord] = (float)$params[$coord];
            }

            return $coordsFromRequest;
        }

        return $this->getCoordinatesDefault($languages);
    }

    /**
     * @param EntitySearchResult $languages
     * @return array
     */
    private function getCoordinatesDefault(EntitySearchResult $languages): array
    {
        if ($languages->count() === 1 && $languages->getEntities()->first() instanceof LanguageEntity &&
            $languages->getEntities()->first()->getLocale()->getCode() === self::ISO_DE_DE) {
            $range = self::LONG_LAT_RANGE[self::ISO_DE_DE];
        } else{
            $range = self::LONG_LAT_RANGE[self::ISO_EN_GB];
        }

        return $range;
    }
}
