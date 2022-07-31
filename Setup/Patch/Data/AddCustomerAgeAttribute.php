<?php
/**
 * Copyright © MageWorx. All rights reserved.
 * See LICENSE.txt for license details.
 */
declare(strict_types=1);

namespace MageWorx\CustomerAge\Setup\Patch\Data;

use Magento\Framework\Setup\Patch\DataPatchInterface;
use Magento\Customer\Model\Customer;
use Magento\Eav\Model\Config;
use Magento\Customer\Setup\CustomerSetupFactory;
use Magento\Framework\Setup\ModuleDataSetupInterface;
use Magento\Customer\Api\CustomerMetadataInterface;

class AddCustomerAgeAttribute implements DataPatchInterface
{
    /**
     * @var CustomerSetupFactory
     */
    private $customerSetupFactory;

    /**
     * @var ModuleDataSetupInterface
     */
    private $setup;

    /**
     * @var Config
     */
    private $eavConfig;

    /**
     * @param ModuleDataSetupInterface $setup
     * @param Config $eavConfig
     * @param CustomerSetupFactory $customerSetupFactory
     */
    public function __construct(
        ModuleDataSetupInterface $setup,
        Config                   $eavConfig,
        CustomerSetupFactory     $customerSetupFactory
    ) {
        $this->customerSetupFactory = $customerSetupFactory;
        $this->setup                = $setup;
        $this->eavConfig            = $eavConfig;
    }

    public function apply()
    {
        $customerSetup  = $this->customerSetupFactory->create(['setup' => $this->setup]);
        $customerEntity = $customerSetup->getEavConfig()->getEntityType(
            CustomerMetadataInterface::ENTITY_TYPE_CUSTOMER
        );
        $attributeSetId = $customerSetup->getDefaultAttributeSetId($customerEntity->getEntityTypeId());
        $attributeGroup = $customerSetup->getDefaultAttributeGroupId(
            $customerEntity->getEntityTypeId(),
            $attributeSetId
        );
        $attributeCode  = 'age';
        $attributeData  = [
            'type'                     => 'int',
            'input'                    => 'text',
            'label'                    => 'Age',
            'required'                 => true,
            'default'                  => 0,
            'visible'                  => true,
            'user_defined'             => true,
            'system'                   => false,
            'position'                 => 1000,
            'is_searchable_in_grid'    => true,
            'is_used_in_grid'          => true,
            'is_visible_in_grid'       => true,
            'is_filterable_in_grid'    => true,
            'is_html_allowed_on_front' => true,
            'visible_on_front'         => true,
            'validate_rules'           => \json_encode(
                [
                    'min_text_length' => 1,
                    'max_text_length' => 3,
                    'input_validation' => 'numeric'
                ]
            )
        ];
        $usedInFormsNew = [
            'adminhtml_customer',
            'customer_account_edit',
            'customer_account_create'
        ];

        $attribute = $customerSetup->getAttribute(Customer::ENTITY, $attributeCode);
        if (empty($attribute)) {
            $customerSetup->addAttribute(Customer::ENTITY, $attributeCode, $attributeData);

            $attribute = $this->eavConfig->getAttribute(Customer::ENTITY, $attributeCode);
            $attribute->addData(
                [
                    'used_in_forms'      => $usedInFormsNew,
                    'attribute_set_id'   => $attributeSetId,
                    'attribute_group_id' => $attributeGroup
                ]
            );

            $attribute->save();
        } else {
            $customerSetup->updateAttribute(
                Customer::ENTITY,
                $attributeCode,
                $attributeData
            );

            $attribute       = $this->eavConfig->getAttribute(Customer::ENTITY, $attributeCode);
            $usedInFormsOrig = $attribute->getData('used_in_forms') ?? [];
            $usedInForms     = array_merge($usedInFormsOrig, $usedInFormsNew);
            $attribute->setData('used_in_forms', $usedInForms);
            $attribute->save();
        }
    }

    /**
     * Patch dependencies
     *
     * @return array
     */
    public static function getDependencies(): array
    {
        return [];
    }

    /**
     * Get aliases (previous names) for the patch.
     *
     * @return string[]
     */
    public function getAliases(): array
    {
        return [];
    }
}
