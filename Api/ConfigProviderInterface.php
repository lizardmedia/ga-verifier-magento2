<?php
/**
 * File: ConfigProviderInterface.php
 *
 * @author Maciej Sławik <maciej.slawik@lizardmedia.pl>
 * @copyright Copyright (C) 2018 Lizard Media (http://lizardmedia.pl)
 */
declare(strict_types=1);

namespace LizardMedia\GoogleAnalyticsVerifier\Api;

use LizardMedia\GoogleAnalyticsVerifier\Model\Data\RewriteArray;

/**
 * Interface ConfigProviderInterface
 * @package LizardMedia\GoogleAnalyticsVerifier\Api
 */
interface ConfigProviderInterface
{
    /**
     * @return string
     */
    public function getVerificationCode(): string;

    /**
     * @return RewriteArray
     */
    public function getRewritesDataArray(): RewriteArray;
}
