<?php
/**
 * File: RewriteInterface.php
 *
 * @author Maciej Sławik <maciej.slawik@lizardmedia.pl>
 * @copyright Copyright (C) 2018 Lizard Media (http://lizardmedia.pl)
 */
declare(strict_types=1);

namespace LizardMedia\GoogleAnalyticsVerifier\Api\Data;

/**
 * Interface RewriteInterface
 * @package LizardMedia\GoogleAnalyticsVerifier\Api\Data
 */
interface RewriteInterface
{
    const FILENAME = 'file_name';
    const FILECONTENT = 'file_content';

    /**
     * @return string
     */
    public function getFileName(): string;

    /**
     * @param string $fileName
     * @return void
     */
    public function setFileName(string $fileName);

    /**
     * @return string
     */
    public function getFileContent(): string;

    /**
     * @param string $fileContent
     * @return void
     */
    public function setFileContent(string $fileContent);
}
