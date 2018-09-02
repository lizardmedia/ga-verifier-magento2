<?php
/**
 * File: VerificationFiles.php
 *
 * @author Maciej Sławik <maciej.slawik@lizardmedia.pl>
 * @copyright Copyright (C) 2018 Lizard Media (http://lizardmedia.pl)
 */
declare(strict_types=1);

namespace LizardMedia\GoogleAnalyticsVerifier\Model\Config\Backend;

use LizardMedia\GoogleAnalyticsVerifier\Api\Data\RewriteInterface;
use LizardMedia\GoogleAnalyticsVerifier\Api\Rewrite\GeneratorInterface;
use LizardMedia\GoogleAnalyticsVerifier\Api\Rewrite\PurgerInterface;
use LizardMedia\GoogleAnalyticsVerifier\Model\Data\RewriteArray;
use LizardMedia\GoogleAnalyticsVerifier\Model\Data\RewriteArrayFactory;
use LizardMedia\GoogleAnalyticsVerifier\Model\Data\RewriteFactory;
use Magento\Framework\App\Cache\TypeListInterface;
use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Framework\App\Config\Value;
use Magento\Framework\Data\Collection\AbstractDb;
use Magento\Framework\Model\Context;
use Magento\Framework\Model\ResourceModel\AbstractResource;
use Magento\Framework\Registry;
use Zend\Json\Json;

/**
 * Class VerificationFiles
 * @package LizardMedia\GoogleAnalyticsVerifier\Model\Config\Backend
 */
class VerificationFiles extends Value
{
    /**
     * @var GeneratorInterface
     */
    private $rewriteGenerator;

    /**
     * @var PurgerInterface
     */
    private $rewritePurger;

    /**
     * @var RewriteFactory
     */
    private $rewriteFactory;

    /**
     * @var RewriteArrayFactory
     */
    private $rewriteArrayFactory;

    /**
     * VerificationFiles constructor.
     * @param GeneratorInterface $rewriteGenerator
     * @param PurgerInterface $rewritePurger
     * @param RewriteFactory $rewriteFactory
     * @param RewriteArrayFactory $rewriteArrayFactory
     * @param Context $context
     * @param Registry $registry
     * @param ScopeConfigInterface $config
     * @param TypeListInterface $cacheTypeList
     * @param AbstractResource|null $resource
     * @param AbstractDb|null $resourceCollection
     * @param array $data
     */
    public function __construct(
        GeneratorInterface $rewriteGenerator,
        PurgerInterface $rewritePurger,
        RewriteFactory $rewriteFactory,
        RewriteArrayFactory $rewriteArrayFactory,
        Context $context,
        Registry $registry,
        ScopeConfigInterface $config,
        TypeListInterface $cacheTypeList,
        AbstractResource $resource = null,
        AbstractDb $resourceCollection = null,
        array $data = []
    ) {
        parent::__construct($context, $registry, $config, $cacheTypeList, $resource, $resourceCollection, $data);
        $this->rewriteGenerator = $rewriteGenerator;
        $this->rewritePurger = $rewritePurger;
        $this->rewriteFactory = $rewriteFactory;
        $this->rewriteArrayFactory = $rewriteArrayFactory;
    }

    /**
     * @return \Magento\Framework\App\Config\Value
     */
    public function beforeSave()
    {
        $this->prepareFieldValueForSave();
        return parent::beforeSave();
    }

    /**
     * @return \Magento\Framework\App\Config\Value
     */
    public function afterSave()
    {
        if ($this->isValueChanged()) {
            $this->refreshUrlRewrites();
        }
        return parent::afterSave();
    }

    /**
     * @return void
     */
    public function afterLoad()
    {
        if ($this->getValue()) {
            $this->prepareFieldValueForRender();
        }
        parent::afterLoad();
    }

    /**
     * @return void
     */
    private function prepareFieldValueForRender()
    {
        $this->setValue(Json::decode($this->getValue(), Json::TYPE_ARRAY));
    }

    /**
     * @return void
     */
    private function prepareFieldValueForSave()
    {
        $newValue = $this->getValue();
        if (isset($newValue['__empty'])) {
            unset($newValue['__empty']);
        }
        $this->setValue(Json::encode($newValue));
    }

    /**
     * @return void
     */
    private function refreshUrlRewrites()
    {
        $this->rewritePurger->purgeVerificationFileRewrites();
        $this->rewriteGenerator->generateVerificationFileRewrite(...$this->getRewritesArray());
    }

    /**
     * @return RewriteArray
     */
    private function getRewritesArray(): RewriteArray
    {
        /** @var RewriteArray $rewrites */
        $rewrites = $this->rewriteArrayFactory->create();
        $valueArray = Json::decode($this->getValue(), Json::TYPE_ARRAY);
        foreach ($valueArray as $rowId => $data) {
            if (!($data[RewriteInterface::FILENAME]) || !$data[RewriteInterface::FILECONTENT]) {
                continue;
            }

            /** @var RewriteInterface $rewrite */
            $rewrite = $this->rewriteFactory->create();
            $rewrite->setFileName($data[RewriteInterface::FILENAME]);
            $rewrite->setFileContent($data[RewriteInterface::FILECONTENT]);
            $rewrites->add($rewrite);
        }
        return $rewrites;
    }
}
