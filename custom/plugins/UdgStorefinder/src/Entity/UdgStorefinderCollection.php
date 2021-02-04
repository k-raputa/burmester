<?php declare(strict_types=1);

namespace UdgStorefinder\Entity;

use Shopware\Core\Framework\DataAbstractionLayer\EntityCollection;

/**
 * Class UdgStorefinderCollection
 * @package UdgStorefinder\Entity
 *
 * @method void                      add(UdgStorefinderEntity $entity)
 * @method void                      set(string $key, UdgStorefinderEntity $entity)
 * @method UdgStorefinderEntity[]    getIterator()
 * @method UdgStorefinderEntity[]    getElements()
 * @method UdgStorefinderEntity|null get(string $key)
 * @method UdgStorefinderEntity|null first()
 * @method UdgStorefinderEntity|null last()
 */
class UdgStorefinderCollection extends EntityCollection
{
    /**
     * @return string
     */
    protected function getExpectedClass(): string
    {
        return UdgStorefinderEntity::class;
    }
}
