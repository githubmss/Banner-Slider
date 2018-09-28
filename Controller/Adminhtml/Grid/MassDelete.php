<?php
namespace Magentomobileshop\Bannersliderapp\Controller\Adminhtml\Grid;

use Magentomobileshop\Bannersliderapp\Model\ResourceModel\Grid\CollectionFactory;
use Magento\Backend\App\Action\Context;
use Magento\Framework\Controller\ResultFactory;
use Magento\Ui\Component\MassAction\Filter;

class MassDelete extends \Magento\Backend\App\Action
{
    private $filter;
    private $collectionFactory;//@codingStandardsIgnoreStart
    public function __construct(
        Context $context,
        Filter $filter,
        CollectionFactory $collectionFactory
    ) {
        $this->filter            = $filter;
        $this->collectionFactory = $collectionFactory;
        parent::__construct($context);
    }
    public function execute()
    {
        $this->getRequest()->getParams();
        $collection = $this->filter->getCollection($this->collectionFactory->create());

        foreach ($collection->getItems() as $auctionProduct) {
            $this->saveDataItem($auctionProduct);
        }

        $this->messageManager->addSuccess(
            __('A total of %1 record(s) have been deleted.', count($collection->getItems()))
        );

        return $this->resultFactory->create(ResultFactory::TYPE_REDIRECT)->setPath('*/*/index');
    }
    protected function _isAllowed()
    {
        return $this->_authorization->isAllowed('Magentomobileshop_Bannersliderapp::row_data_delete');
    }//@codingStandardsIgnoreEnd

    private function saveDataItem($auctionProduct)
    {
        $objectData = \Magento\Framework\App\ObjectManager::getInstance();
        $row        = $objectData->get('Magentomobileshop\Bannersliderapp\Model\Grid')
            ->load($auctionProduct->getBannerId());
        $row->delete();
    }
}
