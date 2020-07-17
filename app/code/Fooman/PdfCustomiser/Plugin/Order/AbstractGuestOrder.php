<?php
namespace Fooman\PdfCustomiser\Plugin\Order;

/**
 * @author     Kristof Ringleff
 * @copyright  Copyright (c) 2009 Fooman Limited (http://www.fooman.co.nz)
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
class AbstractGuestOrder extends AbstractPdfPlugin
{

    /**
     * @var \Fooman\PdfCustomiser\Block\OrderFactory
     */
    protected $orderDocumentFactory;

    /**
     * @var \Magento\Sales\Controller\Guest\OrderViewAuthorization
     */
    protected $orderViewAuthorization;

    /**
     * @var \Magento\Sales\Controller\Guest\OrderLoader
     */
    protected $orderLoader;

    /**
     * @var \Magento\Framework\Registry
     */
    protected $registry;

    /**
     * @param \Magento\Framework\Controller\Result\ForwardFactory    $resultForwardFactory
     * @param \Fooman\PdfCore\Model\PdfRenderer                      $pdfRenderer
     * @param \Fooman\PdfCore\Model\PdfFileHandling                  $pdfFileHandling
     * @param \Fooman\PdfCustomiser\Block\OrderFactory               $orderDocumentFactory
     * @param \Magento\Sales\Controller\Guest\OrderViewAuthorization $orderViewAuthorization
     * @param \Magento\Sales\Controller\Guest\OrderLoader            $orderLoader
     * @param \Magento\Framework\Registry                            $registry
     */
    public function __construct(
        \Magento\Framework\Controller\Result\ForwardFactory $resultForwardFactory,
        \Fooman\PdfCore\Model\PdfRenderer $pdfRenderer,
        \Fooman\PdfCore\Model\PdfFileHandling $pdfFileHandling,
        \Fooman\PdfCustomiser\Block\OrderFactory $orderDocumentFactory,
        \Magento\Sales\Controller\Guest\OrderViewAuthorization $orderViewAuthorization,
        \Magento\Sales\Controller\Guest\OrderLoader $orderLoader,
        \Magento\Framework\Registry $registry
    ) {
        parent::__construct($resultForwardFactory, $pdfRenderer, $pdfFileHandling);

        $this->orderDocumentFactory = $orderDocumentFactory;
        $this->orderViewAuthorization = $orderViewAuthorization;
        $this->orderLoader = $orderLoader;
        $this->registry = $registry;
    }
}
