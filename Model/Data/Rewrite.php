<?php
/**
 * File: Rewrite.php
 *
 * @author Maciej Sławik <maciej.slawik@lizardmedia.pl>
 * @copyright Copyright (C) 2018 Lizard Media (http://lizardmedia.pl)
 */
declare(strict_types=1);

namespace LizardMedia\GoogleAnalyticsVerifier\Model\Data;

use LizardMedia\GoogleAnalyticsVerifier\Api\Data\RewriteInterface;
use Magento\Framework\DataObject;

/**
 * Class Rewrite
 * @package LizardMedia\GoogleAnalyticsVerifier\Model\Data
 */
class Rewrite extends DataObject implements RewriteInterface
{
    /**
     * @return string
     */
    public function getFileName(): string
    {
        return $this->getData(self::FILENAME);
    }

    /**
     * @param string $fileName
     * @return void
     */
    public function setFileName(string $fileName)
    {
        $this->setData(self::FILENAME, $fileName);
    }

    /**
     * @return string
     */
    public function getFileContent(): string
    {
        return $this->getData(self::FILECONTENT);
    }

    /**
     * @param string $fileContent
     * @return void
     */
    public function setFileContent(string $fileContent)
    {
        $this->setData(self::FILECONTENT, $fileContent);
    }
}
