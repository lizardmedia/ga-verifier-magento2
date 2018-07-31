<?php
/**
 * File: Generator.php
 *
 * @author Maciej Sławik <maciej.slawik@lizardmedia.pl>
 * @copyright Copyright (C) 2018 Lizard Media (http://lizardmedia.pl)
 */

namespace LizardMedia\GoogleAnalyticsVerifier\Api\Rewrite;

use LizardMedia\GoogleAnalyticsVerifier\Api\Data\RewriteInterface;

/**
 * Interface GeneratorInterface
 * @package LizardMedia\GoogleAnalyticsVerifier\Api\Rewrite
 */
interface GeneratorInterface
{
    /**
     * @param RewriteInterface[] ...$rewrites
     * @return void
     */
    public function generateVerificationFileRewrite(RewriteInterface ...$rewrites);
}
