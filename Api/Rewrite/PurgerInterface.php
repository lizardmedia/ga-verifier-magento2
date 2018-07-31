<?php
/**
 * File: Purger.php
 *
 * @author Maciej Sławik <maciej.slawik@lizardmedia.pl>
 * @copyright Copyright (C) 2018 Lizard Media (http://lizardmedia.pl)
 */

namespace LizardMedia\GoogleAnalyticsVerifier\Api\Rewrite;

/**
 * Interface PurgerInterface
 * @package LizardMedia\GoogleAnalyticsVerifier\Api\Rewrite
 */
interface PurgerInterface
{
    /**
     * @return void
     */
    public function purgeVerificationFileRewrites();
}
