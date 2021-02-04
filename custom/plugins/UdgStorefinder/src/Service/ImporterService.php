<?php declare(strict_types=1);

namespace UdgStorefinder\Service;

use Doctrine\DBAL\Connection;
use Doctrine\DBAL\DBALException;
use Shopware\Core\Framework\Uuid\Exception\InvalidUuidException;
use Shopware\Core\Framework\Uuid\Exception\InvalidUuidLengthException;
use Shopware\Core\Framework\Uuid\Uuid;
use Shopware\Core\System\SalesChannel\SalesChannelContext;
use Shopware\Core\Framework\DataAbstractionLayer\EntityRepositoryInterface AS EntityRepository;

/**
 * Class LocationService
 * @package UdgStorefinder\Service
 *
 *  $import = new ImporterService(
 *      $context,
 *      $this->get(Connection::class),
 *      $this->get('country.repository'),
 *      $this->get('udg_storefinder.repository')
 * );
 * $import->createStorefinderLocations();
 */
class ImporterService
{
    /**
     * @var Connection
     */
    public $connection;

    /**
     * @var EntityRepository
     */
    public $countryRepository;

    /**
     * @var EntityRepository
     */
    public $storefinderRepository;

    /**
     * @var SalesChannelContext
     */
    public $context;

    /**
     * @var array
     */
    public $resultsTranslation = array(
        24 => 'company',
        25 => 'street',
        26 => 'location',
        27 => 'phone',
        28 => 'email',
        //54 => 'country',
        //69 => ???,
        73 => 'web',
        74 => 'country',
        75 => 'latitude',
        76 => 'longitude',
        77 => 'distrotype',
        88 => 'productline',
    );

    /**
     * @var array
     */
    public $translatedProperties = array(
        'company',
        'street',
        'location',
        'email',
        'phone',
        'web'
    );


    /**
     * ImporterService constructor.
     * @param Connection $connection
     */
    public function __construct(SalesChannelContext $context, Connection $connection, EntityRepository $countryRepository, EntityRepository $storefinderRepository)
    {
        $this->context = $context;
        $this->connection = $connection;
        $this->countryRepository = $countryRepository;
        $this->storefinderRepository = $storefinderRepository;
    }


    /**
     * @throws DBALException
     * @throws InvalidUuidException
     * @throws InvalidUuidLengthException
     */
    public function updateCountries(): void
    {
        $resultsLanguages = $this->connection
            ->executeQuery('SELECT * FROM `language` WHERE 1;')
            ->fetchAll();

        $missing = array(
            'bg' => 'Bulgaria',
            'cn' => 'China',
            'cl' => 'Chile',
            'hk' => 'Hong Kong',
            'id' => 'Indonesia',
            'in' => 'India',
            'kr' => 'South Korea',
            'lb' => 'Lebanon',
            'lk' => 'Sri Lanka',
            'mn' => 'Mongolia',
            'mo' => 'Macau',
            'my' => 'Malaysia',
            'ph' => 'Philippines',
            'rs' => 'Serbia',
            'ru' => 'Russian Federation',
            'sg' => 'Singapore',
            'si' => 'Slovenia',
            'th' => 'Thailand',
            'tw' => 'Taiwan',
            'ua' => 'Ukraine',
            'vn' => 'Vietnam'
        );

        foreach ($resultsLanguages as $index => $result) {
            $resultsLanguages[$index] = Uuid::fromBytesToHex($result['id']);
        }

        $newCountries = array();

        foreach ($missing as $iso => $name) {
            $country = $this->connection
                ->executeQuery('SELECT * FROM `udg_list_countries` WHERE `country_iso2` = \'' . strtoupper($iso) . '\';')
                ->fetch();

            $newCountry = array(
                'iso' => $country['country_iso2'],
                'position' => 111,
                'tax_free' => 0,
                'active' => true,
                'iso3' => $country['country_iso3'],
                'display_state_in_registration' => 0,
                'force_state_in_registration' => 0,
                'name' => array()
            );

            foreach ($resultsLanguages as $language) {
                $newCountry['name'][$language] = ($language != '2fbb5fe2e29a4d70aa5854ce7ce3e20b' ? $country['country_title'] : $name);
            }

            $newCountries[] = $newCountry;
        }

        try {
            $this->countryRepository->create($newCountry, $this->context->getContext());
        } catch (\Exception $e) {
            var_dump($e->getMessage());
            var_dump($e->getLine());
            var_dump($e->getFile());
        }
    }

    /**
     * @throws DBALException
     * @throws InvalidUuidException
     * @throws InvalidUuidLengthException
     */
    public function createStorefinderLocations(): void
    {
        $resultsLocationsFormatted = array();
        $resultsLocations = $this->connection
            ->executeQuery('SELECT * FROM `udg_bm_contentvalues` WHERE 1 ORDER BY `contentid` ASC, `id` ASC;')
            ->fetchAll();

        $resultsLanguagesFormatted = array();
        $resultsLanguages = $this->connection
            ->executeQuery('SELECT * FROM `language` WHERE 1;')
            ->fetchAll();

        $resultsCountriesFormatted = array();
        $resultsCountries = $this->connection
            ->executeQuery('SELECT * FROM `country` WHERE 1;')
            ->fetchAll();

        foreach ($resultsCountries as $result) {
            $result['iso'] = strtolower($result['iso']);
            $result['id'] = Uuid::fromBytesToHex($result['id']);

            $resultsCountriesFormatted[$result['iso']] = $result;
        }

        foreach ($resultsLanguages as $index => $result) {
            $resultsLanguagesFormatted[$index] = Uuid::fromBytesToHex($result['id']);
        }

        foreach ($resultsLocations as $result) {
            $contentId = $result['contentid'];
            $varId = $result['tmplvarid'];

            if (array_key_exists($contentId, $resultsLocationsFormatted) === false) {
                $resultsLocationsFormatted[$contentId] = array(
                    'countryId' => $resultsCountriesFormatted['de']['id'],
                    'productline' => null,
                    'active' => true
                );
            }

            if (array_key_exists($varId, $this->resultsTranslation) === true) {
                if ($varId == 74) {
                    if (array_key_exists($result['value'], $resultsCountriesFormatted) === true) {
                        $resultsLocationsFormatted[$contentId]['countryId'] =
                            $resultsCountriesFormatted[$result['value']]['id'];
                    }

                    continue;
                }

                $resultsLocationsFormatted[$contentId][$this->resultsTranslation[$varId]] = $result['value'];
            }
        }

        foreach ($resultsLocationsFormatted as $contentid => $result) {
            foreach ($this->translatedProperties as $index) {
                if (array_key_exists($index, $result) === false) {
                    continue;
                }

                $value = $result[$index];

                $resultsLocationsFormatted[$contentid][$index] = array();

                foreach ($resultsLanguagesFormatted as $language) {
                    $resultsLocationsFormatted[$contentid][$index][$language] = $value;
                }
            }
        }

        $this->connection->executeQuery('DELETE FROM `udg_storefinder` WHERE 1;');

        try {
            foreach ($resultsLocationsFormatted as $item) {
                $this->storefinderRepository->create(array($item), $this->context->getContext());
            }
        } catch (\Exception $e) {
            var_dump($e->getMessage());
            var_dump($e->getLine());
            var_dump($e->getFile());
            var_dump($item);
        }
    }
}
