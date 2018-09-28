<?php

namespace Magentomobileshop\Bannersliderapp\Model\ResourceModel\Grid;

class CollectionData extends \Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection
{
    /**
     * @var string
     */
    //@codingStandardsIgnoreStart
    private $idFieldName = 'entity_id';
    /**
     * Define resource model.
     */
    protected function _construct()
    {
        $this->_init('
        Magentomobileshop\Bannersliderapp\Model\Grid', 'Magentomobileshop\Bannersliderapp\Model\ResourceModel\Grid');
    }//@codingStandardsIgnoreEnd
}
