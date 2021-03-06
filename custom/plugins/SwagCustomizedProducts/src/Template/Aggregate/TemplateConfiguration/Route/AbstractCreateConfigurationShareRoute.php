<?php declare(strict_types=1);
/*
 * (c) shopware AG <info@shopware.com>
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Swag\CustomizedProducts\Template\Aggregate\TemplateConfiguration\Route;

use Shopware\Core\System\SalesChannel\SalesChannelContext;
use Symfony\Component\HttpFoundation\Request;

abstract class AbstractCreateConfigurationShareRoute
{
    abstract public function getDecorated(): AbstractCreateConfigurationShareRoute;

    abstract public function createConfigurationShare(Request $request, SalesChannelContext $context): ConfigurationShareCreatedResponse;
}
