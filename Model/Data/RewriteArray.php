<?php
/**
 * File: RewriteArray.php
 *
 * @author Maciej Sławik <maciej.slawik@lizardmedia.pl>
 * @copyright Copyright (C) 2018 Lizard Media (http://lizardmedia.pl)
 */
declare(strict_types=1);

namespace LizardMedia\GoogleAnalyticsVerifier\Model\Data;

use LizardMedia\GoogleAnalyticsVerifier\Api\Data\RewriteInterface;

/**
 * Class RewriteArray
 * @package LizardMedia\GoogleAnalyticsVerifier\Model\Data
 */
class RewriteArray extends \IteratorIterator
{
    /**
     * RewriteArray constructor.
     */
    public function __construct()
    {
        parent::__construct(new \ArrayIterator());
    }

    /**
     * @return RewriteInterface
     */
    public function current(): RewriteInterface
    {
        return parent::current();
    }

    /**
     * @param RewriteInterface $rewrite
     */
    public function add(RewriteInterface $rewrite)
    {
        $arrayIterator = $this->getInnerIterator();
        /** @var $arrayIterator \ArrayIterator */
        $arrayIterator->append($rewrite);
    }

    /**
     * @param int $key
     * @param RewriteInterface $rewrite
     */
    public function set(int $key, RewriteInterface $rewrite)
    {
        $arrayIterator = $this->getInnerIterator();
        /** @var $arrayIterator \ArrayIterator */
        $arrayIterator->offsetSet($key, $rewrite);
    }
}
