<?php
declare(strict_types=1);
/**
 * File:PurgerTest.php
 *
 * @author Michał Kobierzyński <michal.kobierzynski@lizardmedia.pl>
 * @author Michał Broniszewski <michal.broniszewski@lizardmedia.pl>
 * @copyright Copyright (C) 2018 Lizard Media (http://lizardmedia.pl)
 */

namespace LizardMedia\GoogleAnalyticsVerifier\Test\Unit\Model\Rewrite;

use PHPUnit_Framework_MockObject_MockObject as MockObject;
use PHPUnit\Framework\TestCase;
use Magento\UrlRewrite\Model\ResourceModel\UrlRewriteCollectionFactory;
use Magento\UrlRewrite\Model\ResourceModel\UrlRewrite as UrlRewriteResource;
use LizardMedia\GoogleAnalyticsVerifier\Model\Rewrite\Purger;
use Magento\UrlRewrite\Model\ResourceModel\UrlRewriteCollection;
use LizardMedia\GoogleAnalyticsVerifier\Api\Rewrite\RewriteDataInterface;
use LizardMedia\GoogleAnalyticsVerifier\Controller\File\Index;
use Magento\UrlRewrite\Model\UrlRewrite;

/**
 * Class PurgerTest
 * @package LizardMedia\GoogleAnalyticsVerifier\Test\Unit\Model\Rewrite
 */
class PurgerTest extends TestCase
{
    /**
     * @var MockObject|UrlRewrite
     */
    private $urlRewrite;

    /**
     * @var MockObject|UrlRewriteCollectionFactory
     */
    private $urlRewriteCollectionFactory;

    /**
     * @var MockObject|UrlRewriteResource
     */
    private $urlRewriteResource;

    /**
     * @var MockObject|Purger
     */
    private $purger;

    /**
     * @return void
     */
    protected function setUp()
    {
        $this->urlRewriteCollectionFactory = $this->getMockBuilder(UrlRewriteCollectionFactory::class)
            ->disableOriginalConstructor()->getMock();
        $this->urlRewriteResource = $this->getMockBuilder(UrlRewriteResource::class)
            ->disableOriginalConstructor()->setMethods([])->getMock();
        $this->urlRewrite = $this->getMockBuilder(UrlRewrite::class)
            ->disableOriginalConstructor()->getMock();

        $this->purger = new Purger(
            $this->urlRewriteCollectionFactory,
            $this->urlRewriteResource
        );
    }

    /**
     * @throws \Exception
     */
    public function testPurgeVerificationFileRewrites()
    {
        $this->setupCollection();
        $this->urlRewriteResource->expects($this->once())->method('delete')->with($this->urlRewrite);
        $this->purger->purgeVerificationFileRewrites();
    }

    /**
     * @throws \Exception
     */
    public function testPurgeVerificationFileRewritesWhenThrowsExceptionWhileDelete()
    {
        $this->setupCollection();
        $this->urlRewriteResource->expects($this->once())->method('delete')
            ->with($this->urlRewrite)->willThrowException(new \Exception());
        $this->expectException(\Exception::class);

        $this->purger->purgeVerificationFileRewrites();
    }

    /**
     * @return void
     */
    private function setupCollection()
    {
        $rewriteCollection = $this->getMockBuilder(UrlRewriteCollection::class)
            ->disableOriginalConstructor()->getMock();
        $this->urlRewriteCollectionFactory->expects($this->once())->method('create')
            ->willReturn($rewriteCollection);

        $rewriteCollection->expects($this->exactly(2))->method('addFieldToFilter')
            ->withConsecutive(
                [
                    array_keys(RewriteDataInterface::GENERATED_REWRITE_DATA),
                    array_values(RewriteDataInterface::GENERATED_REWRITE_DATA)
                ],
                [
                    'target_path',
                    [
                        'like' => '%' . Index::URL_PATH . Index::FILENAME_PARAM . '/' . '%',
                    ]
                ]
            );

        $rewriteCollection->expects($this->once())->method('getItems')->willReturn([$this->urlRewrite]);
    }
}
