<?php
/**
 * File: Index.php
 *
 * @author Maciej Sławik <maciej.slawik@lizardmedia.pl>
 * @copyright Copyright (C) 2018 Lizard Media (http://lizardmedia.pl)
 */
declare(strict_types=1);

namespace LizardMedia\GoogleAnalyticsVerifier\Controller\File;

use LizardMedia\GoogleAnalyticsVerifier\Api\ConfigProviderInterface;
use LizardMedia\GoogleAnalyticsVerifier\Exception\VerificationFileNotFoundException;
use Magento\Framework\App\Action\Action;
use Magento\Framework\App\Action\Context;
use Magento\Framework\Controller\Result\Raw;
use Magento\Framework\Controller\Result\RawFactory;
use Magento\Framework\Controller\ResultFactory;

/**
 * Class Index
 * @package LizardMedia\GoogleAnalyticsVerifier\Controller\File
 */
class Index extends Action
{
    const URL_PATH = 'lm_google_analytics_verifier/file/index/';
    const FILENAME_PARAM = 'request_path';

    /**
     * @var RawFactory
     */
    protected $rawResultFactory;

    /**
     * @var ConfigProviderInterface
     */
    private $configProvider;

    /**
     * Index constructor.
     * @param Context $context
     * @param RawFactory $rawResultFactory
     * @param ConfigProviderInterface $configProvider
     */
    public function __construct(
        Context $context,
        RawFactory $rawResultFactory,
        ConfigProviderInterface $configProvider
    ) {
        parent::__construct($context);
        $this->rawResultFactory = $rawResultFactory;
        $this->configProvider = $configProvider;
    }

    /**
     * @return Raw
     */
    public function execute(): Raw
    {
        try {
            $verificationFileContent = $this->getVerificationFileContent();
            /** @var Raw $result */
            $result = $this->rawResultFactory->create();
            $result->setContents($verificationFileContent);
            return $result;
        } catch (VerificationFileNotFoundException $e) {
            $this->messageManager->addError($e->getMessage());
            $result = $this->resultFactory->create(ResultFactory::TYPE_REDIRECT);
            $result->setUrl('/');
            return $result;
        }
    }

    /**
     * @return string
     * @throws VerificationFileNotFoundException
     */
    private function getVerificationFileContent(): string
    {
        $requestedFile = $this->getRequest()->getParam('request_path');
        $rewrites = $this->configProvider->getRewritesDataArray();
        foreach ($rewrites as $rewrite) {
            if ($requestedFile === $rewrite->getFileName()) {
                return $rewrite->getFileContent();
            }
        }
        throw new VerificationFileNotFoundException(__('Requested verification file was not found'));
    }
}
