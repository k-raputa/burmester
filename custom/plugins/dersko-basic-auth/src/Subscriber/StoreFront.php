<?php declare(strict_types=1);

namespace derskoBasicAuthSW6\Subscriber;

use Shopware\Core\Framework\Context;
use Shopware\Core\Framework\DataAbstractionLayer\Search\Criteria;
use Shopware\Core\Framework\DataAbstractionLayer\Search\Filter\EqualsFilter;
use Shopware\Core\System\SystemConfig\SystemConfigService;
use Shopware\Core\System\User\UserEntity;
use Shopware\Core\Framework\DataAbstractionLayer\EntityRepository;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\RequestEvent;
use Symfony\Component\HttpKernel\KernelEvents;


class StoreFront implements EventSubscriberInterface
{
    /**
     * @var SystemConfigService
     */
    private $config;

    /**
     * @var EntityRepository
     */
    private $userRepo;


    public function __construct(SystemConfigService $systemConfigService, EntityRepository $userRepo)
    {
        $this->userRepo = $userRepo;
        $this->config = $systemConfigService;
    }

    /**
     * @return array
     */
    public static function getSubscribedEvents()
    {
        return [
            KernelEvents::REQUEST => 'onKernelRequest',
        ];
    }

    /**
     * called when the kernel receives a request. Checks if the plugin is active, and if so, queries the user for
     * credentials (only on sales channel requests, backend requests to the admin interface are ignored)
     *
     * @param RequestEvent $event
     *
     * @throws \Shopware\Core\Framework\DataAbstractionLayer\Exception\InconsistentCriteriaIdsException
     */
    public function onKernelRequest(RequestEvent $event)
    {
        $requestAttributes = $event->getRequest()->attributes->all();
        if(!isset($requestAttributes['sw-sales-channel-id']))
        {
            return;
        }

        $isActive = $this->config->get('derskoBasicAuthSW6.config.active');
        if($isActive !== true)
        {
            return;
        }

        if($this->isIPAllowed($event->getRequest()->getClientIp()))
        {
            return;
        }

        $username = $event->getRequest()->server->get('PHP_AUTH_USER', $event->getRequest()->server->get('HTTP_PHP_AUTH_USER', null));
        $password = $event->getRequest()->server->get('PHP_AUTH_PW', $event->getRequest()->server->get('HTTP_PHP_AUTH_PW', null));

        if($username === null || $password === null)
        {
            $this->requireAuth();
        }

        $criteria = new Criteria();
        $criteria->addFilter(
            new EqualsFilter(
                'username',
                $username
            )
        );

        $context = Context::createDefaultContext();
        $result = $this->userRepo->search($criteria, $context);
        if(null === $user = $result->first())
        {
            $this->requireAuth();
        }

        /** @var UserEntity $user */
        $user->getPassword();
        if(!hash_equals($user->getPassword(), crypt($password, $user->getPassword())))
        {
            $this->requireAuth();
        }
    }


    /*
     * internal helpers
     */

    /**
     * checks if a given ip is withing the given network
     *
     * @param string $ip
     * @param string $network
     * @param int $cidr
     *
     * @return bool
     */
    private function cidrMatch($ip, $network, $cidr)
    {
        if ((ip2long($ip) & ~((1 << (32 - $cidr)) - 1) ) == ip2long($network))
        {
            return true;
        }

        return false;
    }

    /**
     * checks if the given remote address is within the configured allowed ips
     *
     * @param string $remoteAddress
     *
     * @return bool
     */
    private function isIPAllowed($remoteAddress)
    {
        $allowedIps = $this->config->get('derskoBasicAuthSW6.config.allowedIps');
        if($allowedIps === null)
        {
            $allowedIps = '';
        }
        $allowedIps = array_filter(array_map('trim', explode(',', $allowedIps)));

        foreach($allowedIps as $ip)
        {
            if($remoteAddress === $ip)
            {
                return true;
            }

            if(false !== strpos($ip, '/'))
            {
                list($network, $cidr) = explode('/', $ip);
                if($this->cidrMatch($remoteAddress, $network, intval($cidr)))
                {
                    return true;
                }
            }
        }

        return false;
    }

    /**
     * sends the http basic auth headers and stops execution
     */
    private function requireAuth()
    {
        header('WWW-Authenticate: Basic realm="' . str_replace('"', "'", $this->config->get('derskoBasicAuthSW6.config.realm')) . '"');
        header('HTTP/1.0 401 Unauthorized');
        echo $this->config->get('derskoBasicAuthSW6.config.realm');
        exit;
    }
}
