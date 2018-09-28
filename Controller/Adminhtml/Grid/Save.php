<?php
namespace Magentomobileshop\Bannersliderapp\Controller\Adminhtml\Grid;

use Magento\Backend\App\Action;
use Magento\Framework\App\Filesystem\DirectoryList;
use Magento\Framework\Filesystem;

class Save extends \Magento\Backend\App\Action
{
    const BASE_MEDIA_PATH = 'magentomobileshop/bannersliderapp/images';
    private $storeManager;
    private $fileUploaderFactory;
    private $fileSystem;//@codingStandardsIgnoreStart
    public function __construct(
        \Magento\MediaStorage\Model\File\UploaderFactory $fileUploaderFactory,
        Action\Context $context,
        Filesystem $fileSystem
    ) {

        $this->fileUploaderFactory = $fileUploaderFactory;
        $this->fileSystem           = $fileSystem;
        parent::__construct($context);
    }
    public function execute()
    {
        $data         = $this->getRequest()->getParams();
        $imageRequest = $this->getRequest()->getFiles('thumbnail');
        if (!$data) {
            $this->_redirect('grid/grid/addrow');
            return;
        }
        try {
            $objectData            = \Magento\Framework\App\ObjectManager::getInstance();
            $rowData = $objectData->create('Magentomobileshop\Bannersliderapp\Model\Grid');
            if ($imageRequest) {
                if (isset($imageRequest['name'])) {
                    $fileName = $imageRequest['name'];
                } else {
                    $fileName = '';
                }
            } else {
                $fileName = '';
            }
            if ($imageRequest && !empty($fileName)) {
                try {
                    $uploader = $this->fileUploaderFactory->create(['fileId' => 'thumbnail']);
                    $uploader->setAllowedExtensions(['jpg', 'jpeg', 'gif', 'png']);
                    $uploader->setAllowRenameFiles(false);
                    $uploader->setFilesDispersion(false);
                    $path              = $this->fileSystem
                    ->getDirectoryRead(DirectoryList::MEDIA)->getAbsolutePath('images/');
                    $result            = $uploader->save($path);
                    $data['thumbnail'] = 'images/' . $result['file'];
                } catch (\Exception $e) {
                    if ($e->getCode() == 0) {
                        $this->messageManager->addError($e->getMessage());
                    }
                }
            } else {
                unset($data['thumbnail']);
            }
            $rowData->setData($data);
            if (isset($data['id'])) {
                $rowData->setEntityId($data['id']);
            }
            $rowData->save();
            $this->messageManager->addSuccess(__('Banner data has been successfully saved.'));
        } catch (\Exception $e) {
            $this->messageManager->addError(__($e->getMessage()));
        }
        $this->_redirect('grid/grid/index');
    }
    protected function _isAllowed()
    {
        return $this->_authorization->isAllowed('Magentomobileshop_Bannersliderapp::add_auction');
    }//@codingStandardsIgnoreEnd
}
