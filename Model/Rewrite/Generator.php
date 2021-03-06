<?php
/**
 * File: Generator.php
 *
 * @author Maciej Sławik <maciej.slawik@lizardmedia.pl>
 * @copyright Copyright (C) 2018 Lizard Media (http://lizardmedia.pl)
 */
declare(strict_types=1);

namespace LizardMedia\GoogleAnalyticsVerifier\Model\Rewrite;

use Exception;
use LizardMedia\GoogleAnalyticsVerifier\Api\Data\RewriteInterface;
use LizardMedia\GoogleAnalyticsVerifier\Api\Rewrite\GeneratorInterface;
use LizardMedia\GoogleAnalyticsVerifier\Api\Rewrite\RewriteDataInterface;
use LizardMedia\GoogleAnalyticsVerifier\Controller\File\Index;
use Magento\Framework\Exception\AlreadyExistsException;
use Magento\Store\Api\Data\StoreInterface;
use Magento\Store\Model\StoreManagerInterface;
use Magento\UrlRewrite\Model\ResourceModel\UrlRewrite as UrlRewriteResource;
use Magento\UrlRewrite\Model\UrlRewrite;
use Magento\UrlRewrite\Model\UrlRewriteFactory;
use Magento\UrlRewrite\Service\V1\Data\UrlRewrite as UrlRewriteService;

/**
 * Class Generator
 * @package LizardMedia\GoogleAnalyticsVerifier\Model\Rewrite
 */
class Generator implements GeneratorInterface
{
    /**
     * @var UrlRewriteFactory
     */
    private $urlRewriteFactory;

    /**
     * @var UrlRewriteResource
     */
    private $urlRewriteResource;

    /**
     * @var StoreManagerInterface
     */
    private $storeManager;

    /**
     * Generator constructor.
     * @param UrlRewriteFactory $urlRewriteFactory
     * @param UrlRewriteResource $urlRewriteResource
     * @param StoreManagerInterface $storeManager
     */
    public function __construct(
        UrlRewriteFactory $urlRewriteFactory,
        UrlRewriteResource $urlRewriteResource,
        StoreManagerInterface $storeManager
    ) {
        $this->urlRewriteFactory = $urlRewriteFactory;
        $this->urlRewriteResource = $urlRewriteResource;
        $this->storeManager = $storeManager;
    }

    /**
     * @param RewriteInterface ...$rewrites
     * @throws Exception
     * @throws AlreadyExistsException
     */
    public function generateVerificationFileRewrite(RewriteInterface ...$rewrites)
    {
        $stores = $this->storeManager->getStores();
        foreach ($rewrites as $rewriteDataObject) {
            foreach ($stores as $store) {
                $this->generateRewriteForStore($store, $rewriteDataObject);
            }
        }
    }

    /**
     * @param StoreInterface $store
     * @param RewriteInterface $rewriteDataObject
     * @throws Exception
     * @throws AlreadyExistsException
     */
    private function generateRewriteForStore(StoreInterface $store, RewriteInterface $rewriteDataObject)
    {
        /** @var UrlRewrite $rewrite */
        $rewrite = $this->urlRewriteFactory->create();

        $fileName = $rewriteDataObject->getFileName();
        $rewrite->setData(RewriteDataInterface::GENERATED_REWRITE_DATA);
        $rewrite->setData(UrlRewriteService::STORE_ID, $store->getId());
        $rewrite->setData(UrlRewriteService::REQUEST_PATH, $fileName);
        $rewrite->setData(
            UrlRewriteService::TARGET_PATH,
            Index::URL_PATH . Index::FILENAME_PARAM . '/' . urlencode($fileName)
        );

        $this->urlRewriteResource->save($rewrite);
    }
}
