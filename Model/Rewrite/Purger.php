<?php
/**
 * File: Purger.php
 *
 * @author Maciej Sławik <maciej.slawik@lizardmedia.pl>
 * @copyright Copyright (C) 2018 Lizard Media (http://lizardmedia.pl)
 */
declare(strict_types=1);

namespace LizardMedia\GoogleAnalyticsVerifier\Model\Rewrite;

use LizardMedia\GoogleAnalyticsVerifier\Api\Rewrite\PurgerInterface;
use LizardMedia\GoogleAnalyticsVerifier\Api\Rewrite\RewriteDataInterface;
use LizardMedia\GoogleAnalyticsVerifier\Controller\File\Index;
use Magento\UrlRewrite\Model\ResourceModel\UrlRewrite as UrlRewriteResource;
use Magento\UrlRewrite\Model\ResourceModel\UrlRewriteCollection;
use Magento\UrlRewrite\Model\ResourceModel\UrlRewriteCollectionFactory;
use Magento\UrlRewrite\Model\UrlRewrite;

/**
 * Class Purger
 * @package LizardMedia\GoogleAnalyticsVerifier\Model\Rewrite
 */
class Purger implements PurgerInterface
{
    /**
     * @var UrlRewriteCollectionFactory
     */
    private $urlRewriteCollectionFactory;

    /**
     * @var UrlRewriteResource
     */
    private $urlRewriteResource;

    /**
     * Generator constructor.
     * @param UrlRewriteCollectionFactory $urlRewriteCollectionFactory
     * @param UrlRewriteResource $urlRewriteResource
     */
    public function __construct(
        UrlRewriteCollectionFactory $urlRewriteCollectionFactory,
        UrlRewriteResource $urlRewriteResource
    ) {
        $this->urlRewriteCollectionFactory = $urlRewriteCollectionFactory;
        $this->urlRewriteResource = $urlRewriteResource;
    }

    /**
     * @throws \Exception
     */
    public function purgeVerificationFileRewrites()
    {
        /** @var UrlRewriteCollection $rewriteCollection */
        $rewriteCollection = $this->urlRewriteCollectionFactory->create();

        $rewriteCollection->addFieldToFilter(
            array_keys(RewriteDataInterface::GENERATED_REWRITE_DATA),
            array_values(RewriteDataInterface::GENERATED_REWRITE_DATA)
        );
        $rewriteCollection->addFieldToFilter(
            'target_path',
            [
                'like' => '%' . Index::URL_PATH . Index::FILENAME_PARAM . '/' . '%',
            ]
        );

        /** @var UrlRewrite $rewrite */
        foreach ($rewriteCollection->getItems() as $rewrite) {
            $this->urlRewriteResource->delete($rewrite);
        }
    }
}
