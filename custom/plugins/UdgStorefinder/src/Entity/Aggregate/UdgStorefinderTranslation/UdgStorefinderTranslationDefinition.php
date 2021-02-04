<?php declare(strict_types=1);

namespace UdgStorefinder\Entity\Aggregate\UdgStorefinderTranslation;

use Shopware\Core\Framework\DataAbstractionLayer\EntityTranslationDefinition;
use Shopware\Core\Framework\DataAbstractionLayer\Field\Flag\Required;
use Shopware\Core\Framework\DataAbstractionLayer\Field\StringField;
use Shopware\Core\Framework\DataAbstractionLayer\FieldCollection;
use UdgStorefinder\Entity\UdgStorefinderDefinition;

/**
 * Class UdgStorefinderTranslationDefinition
 * @package UdgStorefinder\Entity\Aggregate\UdgStorefinderTranslation
 */
class UdgStorefinderTranslationDefinition extends EntityTranslationDefinition
{
    /**
     * @return string
     */
    public function getEntityName(): string
    {
        return 'udg_storefinder_translation';
    }

    /**
     * @return string
     */
    public function getCollectionClass(): string
    {
        return UdgStorefinderTranslationCollection::class;
    }

    /**
     * @return string
     */
    public function getEntityClass(): string
    {
        return UdgStorefinderTranslationEntity::class;
    }

    /**
     * @return string
     */
    public function getParentDefinitionClass(): string
    {
        return UdgStorefinderDefinition::class;
    }

    /**
     * @return FieldCollection
     */
    protected function defineFields(): FieldCollection
    {
        return new FieldCollection([
            (new StringField('company', 'company'))->addFlags(new Required()),
            (new StringField('location', 'location'))->addFlags(new Required()),
            (new StringField('street', 'street')),
            (new StringField('phone', 'phone')),
            (new StringField('email', 'email')),
            (new StringField('web', 'web'))
        ]);
    }
}
