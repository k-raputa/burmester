<?php declare(strict_types=1);

namespace UdgStorefinder\Entity;

use Shopware\Core\Framework\DataAbstractionLayer\EntityDefinition;
use Shopware\Core\Framework\DataAbstractionLayer\Field\BoolField;
use Shopware\Core\Framework\DataAbstractionLayer\Field\FkField;
use Shopware\Core\Framework\DataAbstractionLayer\Field\Flag\PrimaryKey;
use Shopware\Core\Framework\DataAbstractionLayer\Field\Flag\Required;
use Shopware\Core\Framework\DataAbstractionLayer\Field\FloatField;
use Shopware\Core\Framework\DataAbstractionLayer\Field\IdField;
use Shopware\Core\Framework\DataAbstractionLayer\Field\LongTextField;
use Shopware\Core\Framework\DataAbstractionLayer\Field\ManyToOneAssociationField;
use Shopware\Core\Framework\DataAbstractionLayer\Field\StringField;
use Shopware\Core\Framework\DataAbstractionLayer\FieldCollection;
use Shopware\Core\Framework\DataAbstractionLayer\Field\TranslatedField;
use Shopware\Core\Framework\DataAbstractionLayer\Field\TranslationsAssociationField;
use Shopware\Core\System\Country\CountryDefinition;
use UdgStorefinder\Entity\Aggregate\UdgStorefinderTranslation\UdgStorefinderTranslationDefinition;

/**
 * Class UdgStorefinderDefinition
 * @package UdgStorefinder\Entity
 */
class UdgStorefinderDefinition extends EntityDefinition
{
    /**
     * @const table name
     */
    public const ENTITY_NAME = 'udg_storefinder';

    /**
     * @return string
     */
    public function getEntityName(): string
    {
        return self::ENTITY_NAME;
    }

    /**
     * @return string
     */
    public function getEntityClass(): string
    {
        return UdgStorefinderEntity::class;
    }

    /**
     * @return string
     */
    public function getCollectionClass(): string
    {
        return UdgStorefinderCollection::class;
    }

    /**
     * @return FieldCollection
     */
    protected function defineFields(): FieldCollection
    {
        return new FieldCollection([
            // udg_storefinder
            (new IdField('id', 'id'))->addFlags(new Required(), new PrimaryKey()),
            //(new StringField('country', 'country'))->addFlags(new Required()),
            (new FkField('country_id', 'countryId', CountryDefinition::class))->addFlags(new Required()),
            (new FloatField('latitude', 'latitude'))->addFlags(new Required()),
            (new FloatField('longitude', 'longitude'))->addFlags(new Required()),
            (new StringField('distrotype', 'distrotype')),
            (new LongTextField('productline', 'productline')),
            (new BoolField('active', 'active')),

            // udg_storefinder_translation
            new TranslatedField('company'),
            new TranslatedField('street'),
            new TranslatedField('location'),
            new TranslatedField('phone'),
            new TranslatedField('email'),
            new TranslatedField('web'),

            // udg_storefinder  associations
            new TranslationsAssociationField(UdgStorefinderTranslationDefinition::class, 'udg_storefinder_id'),
            new ManyToOneAssociationField('country', 'country_id', CountryDefinition::class, 'id', false),
        ]);
    }
}
