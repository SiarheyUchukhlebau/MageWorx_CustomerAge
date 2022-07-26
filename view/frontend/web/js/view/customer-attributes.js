/**
 * Copyright © MageWorx All rights reserved.
 * See License.txt for license details.
 */

define([
    'uiComponent',
    'Magento_Customer/js/customer-data',
    'jquery'
], function (Component, customerData, $) {
    'use strict';

    return Component.extend({

        /** @inheritdoc */
        initialize: function () {
            this._super();

            this.customerAttributes = customerData.get('mw-customer-attributes');
        }
    });
});
