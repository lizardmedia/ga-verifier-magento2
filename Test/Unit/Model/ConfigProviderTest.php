<?php
declare(strict_types=1);
/**
 * File:ConfigProviderTest.php
 *
 * @author Michał Kobierzyński <michal.kobierzynski@lizardmedia.pl>
 * @author Michał Broniszewski <michal.broniszewski@lizardmedia.pl>
 * @copyright Copyright (C) 2018 Lizard Media (http://lizardmedia.pl)
 */

namespace LizardMedia\GoogleAnalyticsVerifier\Test\Unit\Model;

use PHPUnit_Framework_MockObject_MockObject;
use LizardMedia\GoogleAnalyticsVerifier\Model\ConfigProvider;
use LizardMedia\GoogleAnalyticsVerifier\Model\Data\Rewrite;
use LizardMedia\GoogleAnalyticsVerifier\Model\Data\RewriteArrayFactory;
use LizardMedia\GoogleAnalyticsVerifier\Model\Data\RewriteFactory;
use Magento\Framework\App\Config\ScopeConfigInterface;
use PHPUnit\Framework\TestCase;
use LizardMedia\GoogleAnalyticsVerifier\Model\Data\RewriteArray;

/**
 * Class ConfigProviderTest
 * @package LizardMedia\GoogleAnalyticsVerifier\Test\Unit\Model
 */
class ConfigProviderTest extends TestCase
{
    /**
     * @var PHPUnit_Framework_MockObject_MockObject|ScopeConfigInterface
     */
    private $scopeConfig;

    /**
     * @var PHPUnit_Framework_MockObject_MockObject|RewriteFactory
     */
    private $rewriteFactory;

    /**
     * @var PHPUnit_Framework_MockObject_MockObject|RewriteArrayFactory
     */
    private $rewriteArrayFactory;

    /**
     * @var ConfigProvider
     */
    private $configProvider;

    /**
     * @return void
     */
    protected function setUp(): void
    {
        $this->scopeConfig = $this->getMockBuilder(ScopeConfigInterface::class)->getMock();
        $this->rewriteFactory = $this->getMockBuilder(RewriteFactory::class)
            ->disableOriginalConstructor()->getMock();
        $this->rewriteArrayFactory = $this->getMockBuilder(RewriteArrayFactory::class)
            ->disableOriginalConstructor()->getMock();

        $this->configProvider = new ConfigProvider(
            $this->scopeConfig,
            $this->rewriteFactory,
            $this->rewriteArrayFactory
        );
    }

    /**
     * @test
     * @return void
     */
    public function testGetVerificationCodeIsString(): void
    {
        $this->scopeConfig->expects($this->once())->method('getValue')
            ->with(ConfigProvider::XML_PATH_VERIFICATION_CODE)->willReturn('test');
        $expected = 'test';
        $this->assertEquals($expected, $this->configProvider->getVerificationCode());
    }

    /**
     * @test
     * @return void
     */
    public function testGetVerificationCodeIsNull(): void
    {
        $this->scopeConfig->expects($this->once())->method('getValue')
            ->with(ConfigProvider::XML_PATH_VERIFICATION_CODE)->willReturn(null);
        $expected = '';
        $this->assertEquals($expected, $this->configProvider->getVerificationCode());
    }

    /**
     * @test
     * @return void
     */
    public function testGetRewritesDataArray(): void
    {
        $value = '{"_1533030582256_256":{"file_name":"google800000000","file_content":"dupa1"}}';
        $this->scopeConfig->expects($this->once())
            ->method('getValue')
            ->with(ConfigProvider::XML_PATH_VERIFICATION_FILES)
            ->willReturn($value);

        $rewriteArray = $this->getMockBuilder(RewriteArray::class)
            ->setMethods(null)
            ->getMock();
        $this->rewriteArrayFactory
            ->expects($this->once())
            ->method('create')
            ->willReturn($rewriteArray);
        $rewrite = $this->getMockBuilder(Rewrite::class)->setMethods(null)->getMock();
        $this->rewriteFactory->expects($this->once())->method('create')->willReturn($rewrite);

        $result = $this->configProvider->getRewritesDataArray();
        $result->rewind();

        $this->assertEquals($rewrite, $result->current());
    }
}
