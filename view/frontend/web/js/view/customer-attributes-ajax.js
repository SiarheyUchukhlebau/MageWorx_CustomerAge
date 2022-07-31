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

        initObservable: function () {
            this._super();
            this.observe(this.observableProperties);

            return this;
        },

        initSubscribers: function () {
            this.isVisible = ko.computed(function () {
                return Boolean(this.age());
            }.bind(this));

            return this;
        },

        initData: function () {
            let data = this.getData();

            if (data.age) {
                this.age(data.age);
            } else {
                this.age(false);
            }

            console.log(data);
        },

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

        getProductId: function () {
            return this.productId;
        }
    });
});
