<?php declare(strict_types = 1);

namespace UdgStorefinder\Entity\Aggregate\UdgStorefinderTranslation;

use Shopware\Core\Framework\DataAbstractionLayer\EntityCollection;

/**
 * Class UdgStorefinderTranslationCollection
 * @package UdgStorefinder\Entity\Aggregate\UdgStorefinderTranslation
 *
 * @method void                                 add(UdgStorefinderTranslationEntity $entity)
 * @method void                                 set(string $key, UdgStorefinderTranslationEntity $entity)
 * @method UdgStorefinderTranslationEntity[]    getIterator()
 * @method UdgStorefinderTranslationEntity[]    getElements()
 * @method UdgStorefinderTranslationEntity|null get(string $key)
 * @method UdgStorefinderTranslationEntity|null first()
 * @method UdgStorefinderTranslationEntity|null last()
 */
class UdgStorefinderTranslationCollection extends EntityCollection
{
    /**
     * @return string
     */
    protected function getExpectedClass(): string
    {
        return UdgStorefinderTranslationEntity::class;
    }
}
