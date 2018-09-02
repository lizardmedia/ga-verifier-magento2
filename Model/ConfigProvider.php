<?php
/**
 * File: ConfigProvider.php
 *
 * @author Maciej Sławik <maciej.slawik@lizardmedia.pl>
 * @copyright Copyright (C) 2018 Lizard Media (http://lizardmedia.pl)
 */
declare(strict_types=1);

namespace LizardMedia\GoogleAnalyticsVerifier\Model;

use LizardMedia\GoogleAnalyticsVerifier\Api\ConfigProviderInterface;
use LizardMedia\GoogleAnalyticsVerifier\Api\Data\RewriteInterface;
use LizardMedia\GoogleAnalyticsVerifier\Model\Data\RewriteArray;
use LizardMedia\GoogleAnalyticsVerifier\Model\Data\RewriteArrayFactory;
use LizardMedia\GoogleAnalyticsVerifier\Model\Data\RewriteFactory;
use Magento\Framework\App\Config\ScopeConfigInterface;
use Zend\Json\Json;

/**
 * Class ConfigProvider
 * @package LizardMedia\GoogleAnalyticsVerifier\Model
 */
class ConfigProvider implements ConfigProviderInterface
{
    const XML_PATH_VERIFICATION_CODE = 'lizardmedia_google_analytics_verifier/head/verification_code';
    const XML_PATH_VERIFICATION_FILES = 'lizardmedia_google_analytics_verifier/file/files';

    /**
     * @var ScopeConfigInterface
     */
    private $scopeConfig;

    /**
     * @var RewriteFactory
     */
    private $rewriteFactory;

    /**
     * @var RewriteArrayFactory
     */
    private $rewriteArrayFactory;

    /**
     * GeneralConfigProvider constructor.
     * @param ScopeConfigInterface $scopeConfig
     * @param RewriteFactory $rewriteFactory
     * @param RewriteArrayFactory $rewriteArrayFactory
     */
    public function __construct(
        ScopeConfigInterface $scopeConfig,
        RewriteFactory $rewriteFactory,
        RewriteArrayFactory $rewriteArrayFactory
    ) {
        $this->scopeConfig = $scopeConfig;
        $this->rewriteFactory = $rewriteFactory;
        $this->rewriteArrayFactory = $rewriteArrayFactory;
    }

    /**
     * @return string
     */
    public function getVerificationCode(): string
    {
        return (string)$this->scopeConfig->getValue(self::XML_PATH_VERIFICATION_CODE);
    }

    /**
     * @return RewriteArray
     */
    public function getRewritesDataArray(): RewriteArray
    {
        $encodedFiles = $this->scopeConfig->getValue(self::XML_PATH_VERIFICATION_FILES);
        $filesArray = Json::decode((string)$encodedFiles, Json::TYPE_ARRAY);
        /** @var RewriteArray $rewriteArray */
        $rewriteArray = $this->rewriteArrayFactory->create();

        foreach ($filesArray as $fileData) {
            /** @var RewriteInterface $rewrite */
            $rewrite = $this->rewriteFactory->create();
            $rewrite->setFileName($fileData[RewriteInterface::FILENAME]);
            $rewrite->setFileContent($fileData[RewriteInterface::FILECONTENT]);
            $rewriteArray->add($rewrite);
        }

        return $rewriteArray;
    }
}
