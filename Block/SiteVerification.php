<?php
/**
 * File: SiteVerification.php
 *
 * @author Maciej Sławik <maciej.slawik@lizardmedia.pl>
 * @copyright Copyright (C) 2018 Lizard Media (http://lizardmedia.pl)
 */
declare(strict_types=1);

namespace LizardMedia\GoogleAnalyticsVerifier\Block;

use LizardMedia\GoogleAnalyticsVerifier\Api\ConfigProviderInterface;
use Magento\Framework\View\Element\Template;

/**
 * Class SiteVerification
 * @package LizardMedia\GoogleAnalyticsVerifier\Block
 */
class SiteVerification extends Template
{
    /**
     * @var ConfigProviderInterface
     */
    private $configProvider;

    /**
     * SiteVerification constructor.
     * @param ConfigProviderInterface $configProvider
     * @param Template\Context $context
     * @param array $data
     */
    public function __construct(
        ConfigProviderInterface $configProvider,
        Template\Context $context,
        array $data = []
    ) {
        parent::__construct($context, $data);
        $this->configProvider = $configProvider;
    }

    /**
     * @return string
     */
    public function getVerificationCode(): string
    {
        return $this->configProvider->getVerificationCode();
    }
}
