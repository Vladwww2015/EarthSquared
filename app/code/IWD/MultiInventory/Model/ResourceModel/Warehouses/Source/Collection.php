<?php

namespace IWD\MultiInventory\Model\ResourceModel\Warehouses\Source;

use Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection;

/**
 * Class Collection
 * @package IWD\MultiInventory\Model\ResourceModel\Warehouses\Source
 */
class Collection extends AbstractCollection
{
    /**
     * {@inheritdoc}
     */
    protected function _construct()
    {
        $this->_init(
            'IWD\MultiInventory\Model\Warehouses\Source',
            'IWD\MultiInventory\Model\ResourceModel\Warehouses\Source'
        );
    }
}
