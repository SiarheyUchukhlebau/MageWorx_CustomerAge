/**
 * Copyright © MageWorx All rights reserved.
 * See License.txt for license details.
 */

define([
    'uiComponent',
    'jquery',
    'ko'
], function (Component, $, ko) {
    'use strict';

    return Component.extend({

        // Property which will be displayed as example in the template
        observableProperties: [
            'age'
        ],

        /** @inheritdoc */
        initialize: function () {
            this._super();
            this.initObservable();
            this.initSubscribers();
            this.initData();
        },

        /**
         * Make age observable
         *
         * @returns {*}
         */
        initObservable: function () {
            this._super();
            this.observe(this.observableProperties);

            return this;
        },

        /**
         * Make isVisible computed, based on the age value
         *
         * @returns {*}
         */
        initSubscribers: function () {
            this.isVisible = ko.computed(function () {
                return Boolean(this.age());
            }.bind(this));

            return this;
        },

        /**
         * Call for controller on the page load and set data to the component's property: age
         */
        initData: function () {
            let data = this.getData();

            if (data.age) {
                this.age(data.age);
            } else {
                this.age(false);
            }

            console.log(data);
        },

        /**
         * Receive data from controller. Hardcoded.
         *
         * @returns {{}}
         */
        getData: function() {
            let url = window.BASE_URL + "mageworx_customer_age/index/detectAge",
                result = {};

            $.ajax({
                url: url,
                data: {
                    "product_id": this.getProductId()
                },
                type: "GET",
                async: false,
                dataType: "json",
                contentType: "application/json",
                success: function (data, status, xhr) {
                    result = data;
                },
                error: function (request, status, error) {
                    console.log(error);
                },
            });

            return result;

        },

        /**
         * Get current product id from component's configuration. @see \MageWorx\CustomerAge\ViewModel\AgeJs
         * @returns {*}
         */
        getProductId: function () {
            return this.productId;
        }
    });
});
