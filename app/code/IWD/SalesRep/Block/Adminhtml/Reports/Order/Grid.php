<?php

namespace IWD\SalesRep\Block\Adminhtml\Reports\Order;

use \IWD\SalesRep\Model\ResourceModel\Order as SalesrepOrderResource;
use \IWD\SalesRep\Model\ResourceModel\User as SalesrepResource;
use \IWD\SalesRep\Model\User as Salesrep;

/**
 * Class Grid
 * @package IWD\SalesRep\Block\Adminhtml\Reports\Order
 */
class Grid extends \Magento\Backend\Block\Widget\Grid\Extended
{
    /**
     * @var SalesrepOrderResource\Report\CollectionFactory
     */
    private $salesrepOrderCollection;

    /**
     * @var \Magento\Reports\Model\Grouped\CollectionFactory
     */
    private $collectionFactory;

    /**
     * @var \IWD\SalesRep\Helper\Data
     */
    private $helper;

    /**
     * Grid constructor.
     * @param \Magento\Backend\Block\Template\Context $context
     * @param \Magento\Backend\Helper\Data $backendHelper
     * @param SalesrepOrderResource\Report\CollectionFactory $salesrepOrderCollection
     * @param \Magento\Reports\Model\Grouped\CollectionFactory $collectionFactory
     * @param array $data
     */
    public function __construct(
        \Magento\Backend\Block\Template\Context $context,
        \Magento\Backend\Helper\Data $backendHelper,
        \IWD\SalesRep\Model\ResourceModel\Order\Report\CollectionFactory $salesrepOrderCollection,
        \Magento\Reports\Model\Grouped\CollectionFactory $collectionFactory,
        \IWD\SalesRep\Helper\Data $helper,
        array $data = []
    ) {
        parent::__construct($context, $backendHelper, $data);
        $this->salesrepOrderCollection = $salesrepOrderCollection;
        $this->collectionFactory = $collectionFactory;
        $this->helper = $helper;
    }

    /**
     * @inheritdoc
     */
    protected function _construct()
    {
        parent::_construct();
        $this->setCountTotals(true);
        $this->setFilterVisibility(false);
        $this->setPagerVisibility(false);
        $this->setUseAjax(false);
    }

    /**
     * @inheritdoc
     */
    protected function _prepareCollection()
    {
        $filterData = $this->getFilterData();

        if ($filterData->getData('from') === null || $filterData->getData('to') === null) {
            $this->setCountTotals(false);
            $this->setCountSubTotals(false);
            $this->setCollection($this->collectionFactory->create());
            return parent::_prepareCollection();
        }

        $collection = $this->salesrepOrderCollection->create();

        $collection->setPageSize(null);
        
        // apply filters
        if ($filterData->getData('from') != null && $filterData->getData('to') != null) {
            $collection->setDateRange(
                $filterData->getData('from', null),
                $filterData->getData('to', null)
            );
        }

        $orderStatuses = $filterData->getData('order_statuses');
        if (is_array($orderStatuses)) {
            if (count($orderStatuses) == 1 && strpos($orderStatuses[0], ',') !== false) {
                $orderStatuses = explode(',', $orderStatuses[0]);
            }
            $collection->addOrderStatusFilter($orderStatuses);
        }

        if ($filterData->getData('salesrep_id') !== null) {
            $collection->addFieldToFilter(Salesrep::SALESREP_ID, $filterData->getData('salesrep_id'));
        }

        if ($bonus = $this->helper->getBonusCommissionConfig()) {
            $collection->setBonusCommission($bonus);
        }

        $this->setCollection($collection);
        $this->setReportTotals($collection);

        return parent::_prepareCollection();
    }

    /**
     * Custom totals calc
     *
     * @param \Magento\Framework\Data\Collection $collection
     * @return $this
     */
    protected function setReportTotals(\Magento\Framework\Data\Collection $collection)
    {
        if ($this->getCountTotals()) {
            $totalsCollection = clone $collection;
            $totalColumns = [];
            $columns = $this->getColumns();
            foreach ($columns as $columnId => $column) {
                if ($column->getData('total') == 'sum') {
                    $totalColumns[] = $column->getData('index');
                }
            }
            $totals = new \Magento\Framework\DataObject();
            foreach ($totalsCollection->getItems() as $_item) {
                foreach($totalColumns as $columnName) {
                    if($_item->getData($columnName)) {
                        $sum = $totals->getData($columnName) + $_item->getData($columnName);
                        $totals->setData($columnName, $sum);
                    }
                }
            }
            $this->setTotals($totals);
        }

        return $this;
    }

    /**
     * @inheritdoc
     */
    protected function _prepareColumns()
    {
        $this->addColumn(
            'period',
            [
                'index' => 'period',
                'header' => 'Period',
            ]
        );
        $this->addColumn(
            'name',
            [
                'index' => 'name',
                'header' => 'Sales Rep',
            ]
        );
        $this->addColumn(
            'increment_id',
            [
                'index' => 'increment_id',
                'header' => 'Order ID',
            ]
        );

        $this->addColumn(
            'status',
            [
                'index' => 'status',
                'header' => 'Order Status',
            ]
        );

        $this->addColumn(
            'customer_name',
            [
                'index' => 'customer_name',
                'header' => 'Customer',
            ]
        );

        $this->addColumn(
            'total',
            [
                'type' => 'currency',
                'index' => 'total',
                'header' => 'Sales Total',
                'total' => 'sum',
            ]
        );

        $this->addColumn(
            'invoiced',
            [
                'type' => 'currency',
                'index' => 'invoiced',
                'header' => 'Invoiced',
                'total' => 'sum',
            ]
        );
        $this->addColumn(
            'refund',
            [
                'type' => 'currency',
                'index' => 'refund',
                'header' => 'Refund',
                'total' => 'sum',
            ]
        );

        $this->addColumn(
            'commission_desc',
            [
                'index' => 'commission_desc',
                'header' => 'Commission Description',
            ]
        );

        $this->addColumn(
            'commission',
            [
                'type' => 'currency',
                'index' => 'commission',
                'header' => 'Commission',
                'total' => 'sum',
            ]
        );

        $this->addExportType('*/*/exportSalesCsv', __('CSV'));
        $this->addExportType('*/*/exportSalesExcel', __('Excel XML'));

        return parent::_prepareColumns();
    }
}
