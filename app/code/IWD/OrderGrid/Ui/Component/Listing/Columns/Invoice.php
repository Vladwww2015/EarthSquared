<?php

namespace IWD\OrderGrid\Ui\Component\Listing\Columns;

use IWD\OrderGrid\Ui\Component\Listing\Columns;

/**
 * Class Invoice
 * @package IWD\OrderGrid\Ui\Component\Listing\Columns
 */
class Invoice extends Columns
{
    /**
     * Prepare Data Source
     *
     * @param array $dataSource
     * @return array
     */
    public function prepareDataSource(array $dataSource)
    {

        if (isset($dataSource['data']['items']) && is_array(reset($dataSource['data']['items'])) && array_key_exists($this->columnName, reset($dataSource['data']['items']))) {
            foreach ($dataSource['data']['items'] as &$item) {
                $item[$this->columnName] = $this->columnHelper->getAggregatedData(
                    $item['iwd_order_invoice_id'],
                    $item['iwd_order_invoice_number'],
                    'invoice'
                );
            }
        }
        return $dataSource;
    }
}
