<?php
declare(strict_types=1);
/**
 * File:GeneratorTest.php
 *
 * @author Michał Kobierzyński <michal.kobierzynski@lizardmedia.pl>
 * @author Michał Broniszewski <michal.broniszewski@lizardmedia.pl>
 * @copyright Copyright (C) 2018 Lizard Media (http://lizardmedia.pl)
 */

namespace LizardMedia\GoogleAnalyticsVerifier\Test\Unit\Model\Rewrite;

use PHPUnit_Framework_MockObject_MockObject as MockObject;
use PHPUnit\Framework\TestCase;
use Magento\UrlRewrite\Model\UrlRewriteFactory;
use Magento\UrlRewrite\Model\ResourceModel\UrlRewrite as UrlRewriteResource;
use Magento\Store\Model\StoreManagerInterface;
use LizardMedia\GoogleAnalyticsVerifier\Model\Rewrite\Generator;
use Magento\Store\Api\Data\StoreInterface;
use LizardMedia\GoogleAnalyticsVerifier\Model\Data\Rewrite;
use Magento\UrlRewrite\Model\UrlRewrite;
use LizardMedia\GoogleAnalyticsVerifier\Api\Rewrite\RewriteDataInterface;
use LizardMedia\GoogleAnalyticsVerifier\Controller\File\Index;
use Magento\UrlRewrite\Service\V1\Data\UrlRewrite as UrlRewriteService;

/**
 * Class GeneratorTest
 * @package LizardMedia\GoogleAnalyticsVerifier\Test\Unit\Model\Rewrite
 */
class GeneratorTest extends TestCase
{
    const STORE_ID = 1;

    /**
     * @var MockObject|UrlRewrite
     */
    private $urlRewrite;

    /**
     * @var MockObject|UrlRewriteFactory
     */
    private $urlRewriteFactory;

    /**
     * @var MockObject|UrlRewriteResource
     */
    private $urlRewriteResource;

    /**
     * @var MockObject|StoreManagerInterface
     */
    private $storeManager;

    /**
     * @var MockObject|Generator
     */
    private $generator;

    /**
     * @return void
     */
    protected function setUp(): void
    {
        $this->urlRewrite = $this->getMockBuilder(UrlRewrite::class)
            ->disableOriginalConstructor()->getMock();
        $this->urlRewriteFactory = $this->getMockBuilder(UrlRewriteFactory::class)->getMock();
        $this->urlRewriteResource = $this->getMockBuilder(UrlRewriteResource::class)
            ->disableOriginalConstructor()->getMock();
        $this->storeManager = $this->getMockBuilder(StoreManagerInterface::class)
            ->disableOriginalConstructor()->getMock();

        $this->generator = new Generator(
            $this->urlRewriteFactory,
            $this->urlRewriteResource,
            $this->storeManager
        );
    }

    /**
     * @test
     * @return void
     */
    public function testGenerateVerificationFileRewriteCreatesRewrite(): void
    {
        $fileName = 'fileName';
        $rewriteMock = $this->getMockBuilder(Rewrite::class)
            ->disableOriginalConstructor()->getMock();
        $rewriteMock->expects($this->once())->method('getFileName')
            ->willReturn($fileName);

        $this->runGenerateVerificationFileRewriteMethod($fileName, self::STORE_ID);

        $this->urlRewriteResource->expects($this->once())->method('save')->with($this->urlRewrite);
        $this->generator->generateVerificationFileRewrite($rewriteMock);
    }

    /**
     * @test
     * @return void
     */
    public function testGenerateVerificationFileRewriteWhenUrlResourceThrowsException(): void
    {
        $fileName = '';
        $rewriteMock = $this->getMockBuilder(Rewrite::class)
            ->disableOriginalConstructor()->getMock();
        $rewriteMock->expects($this->once())->method('getFileName')
            ->willReturn($fileName);

        $this->runGenerateVerificationFileRewriteMethod($fileName, self::STORE_ID);

        $this->urlRewriteResource->expects($this->once())->method('save')
            ->with($this->urlRewrite)->willThrowException(new \Exception());
        $this->expectException(\Exception::class);

        $this->generator->generateVerificationFileRewrite($rewriteMock);
    }

    /**
     * @param string $fileName
     * @param int $storeId
     * @return void
     */
    private function runGenerateVerificationFileRewriteMethod(string $fileName, int $storeId): void
    {
        $store = $this->getMockBuilder(StoreInterface::class)->getMock();
        $stores = [$store];
        $this->storeManager->expects($this->once())->method('getStores')->willReturn($stores);
        $this->urlRewriteFactory->expects($this->once())->method('create')->willReturn($this->urlRewrite);

        $store->expects($this->once())->method('getId')->willReturn($storeId);

        $this->urlRewrite->expects($this->exactly(4))
            ->method('setData')
            ->withConsecutive(
                [RewriteDataInterface::GENERATED_REWRITE_DATA, null],
                [UrlRewriteService::STORE_ID, $storeId],
                [UrlRewriteService::REQUEST_PATH, $fileName],
                [UrlRewriteService::TARGET_PATH, Index::URL_PATH . Index::FILENAME_PARAM . '/' . $fileName]
            )->willReturn($this->urlRewrite);
    }
}
