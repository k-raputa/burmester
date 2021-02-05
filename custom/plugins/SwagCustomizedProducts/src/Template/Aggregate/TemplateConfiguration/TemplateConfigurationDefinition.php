<?php declare(strict_types=1);
/*
 * (c) shopware AG <info@shopware.com>
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Swag\CustomizedProducts\Template\Aggregate\TemplateConfiguration;

use Shopware\Core\Framework\DataAbstractionLayer\EntityDefinition;
use Shopware\Core\Framework\DataAbstractionLayer\Field\FkField;
use Shopware\Core\Framework\DataAbstractionLayer\Field\Flag\PrimaryKey;
use Shopware\Core\Framework\DataAbstractionLayer\Field\Flag\Required;
use Shopware\Core\Framework\DataAbstractionLayer\Field\IdField;
use Shopware\Core\Framework\DataAbstractionLayer\Field\JsonField;
use Shopware\Core\Framework\DataAbstractionLayer\Field\ManyToOneAssociationField;
use Shopware\Core\Framework\DataAbstractionLayer\Field\OneToManyAssociationField;
use Shopware\Core\Framework\DataAbstractionLayer\Field\ReferenceVersionField;
use Shopware\Core\Framework\DataAbstractionLayer\Field\StringField;
use Shopware\Core\Framework\DataAbstractionLayer\FieldCollection;
use Swag\CustomizedProducts\Template\Aggregate\TemplateConfiguration\Aggregate\TemplateConfigurationShareDefinition;
use Swag\CustomizedProducts\Template\TemplateDefinition;

class TemplateConfigurationDefinition extends EntityDefinition
{
    public const ENTITY_NAME = 'swag_customized_products_template_configuration';

    public function getEntityName(): string
    {
        return self::ENTITY_NAME;
    }

    public function getEntityClass(): string
    {
        return TemplateConfigurationEntity::class;
    }

    public function getCollectionClass(): string
    {
        return TemplateConfigurationCollection::class;
    }

    protected function defineFields(): FieldCollection
    {
        return new FieldCollection([
            (new IdField('id', 'id'))->addFlags(new Required(), new PrimaryKey()),
            (new ReferenceVersionField(TemplateDefinition::class, 'template_version_id'))->addFlags(new Required()),

            (new StringField('hash', 'hash', 32))->addFlags(new Required()),
            (new JsonField('configuration', 'configuration'))->addFlags(new Required()),

            (new FkField('template_id', 'templateId', TemplateDefinition::class))->addFlags(new Required()),
            new ManyToOneAssociationField('template', 'template_id', TemplateDefinition::class),

            new OneToManyAssociationField('templateConfigurationShares', TemplateConfigurationShareDefinition::class, 'template_configuration_id'),
        ]);
    }
}
