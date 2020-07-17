<?php
/**
 * @copyright Copyright (c) 2015 Fooman Limited (http://www.fooman.co.nz)
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Fooman\PdfCustomiser\Model\Config\Backend;

use Magento\Framework\App\Config\Data\ProcessorInterface;
use Magento\Framework\App\Config\ScopeConfigInterface;

class GroupContent extends \Magento\Framework\App\Config\Value implements ProcessorInterface
{
    public function beforeSave()
    {
        $values = $this->getValue();
        if ($values) {
            if (!is_array($values)) {
                $values = json_decode($values, true);
            }
            $check = [];
            foreach ($values as $value) {
                if (isset($value['group_id'])) {
                    if (isset($check[$value['group_id']])) {
                        throw new \Magento\Framework\Exception\LocalizedException(
                            __('Each customer group can only appear once.')
                        );
                    } else {
                        $check[$value['group_id']] = true;
                    }
                }
            }
        }
        if (is_array($values)) {
            unset($values['__empty']);
            $this->setValue(json_encode($values));
        }

        parent::beforeSave();
    }

    // phpcs:ignore PSR2.Methods.MethodDeclaration.Underscore -- Magento 2 Core use
    protected function _afterLoad()
    {
        $values = $this->getValue();
        if (!is_array($values)) {
            $values = json_decode($values, true);
            $this->setValue(empty($values) ? false : $values);
        }
    }

    public function getOldValue()
    {
        return $this->_config->getValue(
            $this->getPath(),
            $this->getScope() ?: ScopeConfigInterface::SCOPE_TYPE_DEFAULT,
            $this->getScopeCode()
        );
    }

    public function processValue($value)
    {
        if (!empty($value) && !is_array($value) && is_string($value)) {
            $values = json_decode($value, true);
            if ($values) {
                return $values;
            }
        }
        return $value;
    }
}
