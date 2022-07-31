<?php
/**
 * Copyright © MageWorx. All rights reserved.
 * See LICENSE.txt for license details.
 */
declare(strict_types=1);

namespace MageWorx\CustomerAge\Controller\Index;

use Magento\Framework\App\Action\HttpGetActionInterface;
use Magento\Framework\App\Request\Http;
use Magento\Framework\App\ResponseInterface;

class DetectAge implements HttpGetActionInterface
{
    /**
     * @var Http
     */
    protected $request;
    /**
     * @var \Magento\Framework\Controller\ResultFactory
     */
    protected $resultFactory;
    /**
     * @var \Magento\Customer\Model\Session
     */
    protected $customerSession;

    /**
     * @param Http $request
     * @param \Magento\Framework\Controller\ResultFactory $resultFactory
     * @param \Magento\Customer\Model\Session $customerSession
     */
    public function __construct(
        \Magento\Framework\App\Request\Http         $request,
        \Magento\Framework\Controller\ResultFactory $resultFactory,
        \Magento\Customer\Model\Session             $customerSession
    ) {
        $this->request         = $request;
        $this->resultFactory   = $resultFactory;
        $this->customerSession = $customerSession;
    }

    /**
     * Execute action based on request and return result
     *
     * @return \Magento\Framework\Controller\ResultInterface|ResponseInterface
     * @throws \Magento\Framework\Exception\NotFoundException
     */
    public function execute()
    {
        $customerId = (int)$this->customerSession->getCustomerId();
        $productId  = (int)$this->request->getParam('product_id', 0);

        $result = $this->resultFactory->create(
            \Magento\Framework\Controller\ResultFactory::TYPE_JSON
        );

        // Call here your model and return correct data to the component

        // For testing: Dummy data + input data
        $result->setData(
            [
                'age'         => 133,
                'customer_id' => $customerId,
                'product_id'  => $productId
            ]
        );

        return $result;
    }
}
