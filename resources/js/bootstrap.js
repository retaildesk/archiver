window._ = require('lodash');
import $ from "expose-loader?exposes=$,jQuery!jquery";
import 'expose-loader?exposes=$,jQuery!jquery';
import { app } from "./app.js";
import { transaction } from "./transaction.js";
import datatable from './datatable.js';
require('./bootstrap');
require('datatables.net-bs4');
require('datatables.net-buttons-bs4');
import 'daterangepicker/daterangepicker.js';

/**
 * We'll load the axios HTTP library which allows us to easily issue requests
 * to our Laravel back-end. This library automatically handles sending the
 * CSRF token as a header based on the value of the "XSRF" token cookie.
 */

window.axios = require('axios');

window.axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';

$( document ).ready(function() {
  app.init();
});
