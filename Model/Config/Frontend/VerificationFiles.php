<?php
/**
 * File: VerificationFiles.php
 *
 * @author Maciej Sławik <maciej.slawik@lizardmedia.pl>
 * @copyright Copyright (C) 2018 Lizard Media (http://lizardmedia.pl)
 */
declare(strict_types=1);

namespace LizardMedia\GoogleAnalyticsVerifier\Model\Config\Frontend;

use Magento\Config\Block\System\Config\Form\Field\FieldArray\AbstractFieldArray;

/**
 * Class VerificationFiles
 * @package LizardMedia\GoogleAnalyticsVerifier\Model\Config\Frontend
 */
class VerificationFiles extends AbstractFieldArray
{
    /**
     * @var bool
     * @override
     */
    protected $_addAfter = false;

    /**
     * @return void
     */
    protected function _prepareToRender()
    {
        $this->addColumn(
            'file_name',
            [
                'label' => __('File name'),
                'size' => 20,
            ]
        );
        $this->addColumn(
            'file_content',
            [
                'label' => __('File content'),
                'size' => 50,
            ]
        );
        parent::_prepareToRender();
    }
}
