<?php
/**
 * Copyright © MageWorx. All rights reserved.
 * See LICENSE.txt for license details.
 */
declare(strict_types=1);

namespace MageWorx\CustomerAge\ViewModel;

use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\View\Element\Block\ArgumentInterface;

class AgeJs implements ArgumentInterface
{
    /**
     * @var \Magento\Store\Model\StoreManagerInterface
     */
    protected $storeManager;

    /**
     * @var \Magento\Framework\Registry
     */
    protected $registry;

    /**
     * @param \Magento\Store\Model\StoreManagerInterface $storeManager
     */
    public function __construct(
        \Magento\Store\Model\StoreManagerInterface $storeManager,
        \Magento\Framework\Registry                $registry
    ) {
        $this->storeManager = $storeManager;
        $this->registry     = $registry;
    }

    /**
     * @return int
     */
    public function getCurrentProductId(): int
    {
        $product = $this->registry->registry('current_product');
        if (!$product) {
            return 0;
        }

        return (int)$product->getId();
    }

    /**
     * Retrieve store code
     *
     * @return string
     */
    public function getStoreCode(): string
    {
        try {
            return $this->storeManager->getStore()->getCode();
        } catch (LocalizedException $localizedException) {
            return 'default';
        }
    }
}
