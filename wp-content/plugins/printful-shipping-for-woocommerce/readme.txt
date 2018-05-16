=== Printful Integration for WooCommerce ===
Contributors: girts_u, kievins
Tags: woocommerce, printful, drop shipping, shipping, shipping rates, fulfillment, printing, fedex, carriers, checkout, t-shirts
Requires at least: 3.8
Tested up to: 4.8
Stable tag: 2.0.1
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

See order statistics, automate tax settings and enable live shipping rates for your customers.

== Description ==

See your order, revenue and profit statistics on WooCommerce Printful's dashboard, automate tax settings and enable live shipping rates from carriers like FedEx on your WooCommerce checkout page. These rates are identical to the list you get when you submit an order manually and are specific to the shipping address your customer provides when checking out.

== Installation ==
1. Upload 'printful-shipping-for-woocommerce' to the '/wp-content/plugins/' directory
1. Activate the plugin through the 'Plugins' menu in WordPress
1. Click the "Connect" button or add your Printful API key manually to Printful->Settings tab
1. Enable shipping rate calculation in WooCommerce->Settings->Shipping->Printful Shipping tab
1. To automatically calculate taxes please check 'Enable taxes and tax calculations' under WooCommerce Tax settings.
1. Then go to Printful->Settings tab and check 'Calculated for all products shipped to North Carolina and California'.

== Frequently Asked Questions ==

= How do I get Printful API key? =

Go to https://www.printful.com/dashboard/store , select your WooCommerce store, click "Edit" and then click "Enable API Access". Your API key will be generated and displayed there.

== Screenshots ==

1. Connect to Printful
2. Printful dashboard
3. Integration settings
4. Plugin status page
5. Support page
6. Shipping live rates in cart

== Upgrade Notice ==

= 2.0.1 =
Fixed various minor issues

= 2.0 =
New major plugin version

= 1.2.8 =
Fixed bug that caused tax rates to become invisible on checkout since WC 3.0

= 1.2.7 =
Do not calculate shipping rates for US addresses while ZIP or state is not entered

= 1.2.6 =
Include shipping rates in tax calculation for states that require that

= 1.2.5 =
Added option to allow Woocommerce default rates together with Printful rates for Printful products

= 1.2.4 =
Prevent virtual products from requiring shipping rate when bought together with Printful products

= 1.2.3 =
Fixed issue introduced in 1.2.2

= 1.2.2 =
Fixed PHP warning on Woocommerce 2.6

= 1.2.1 =
Fixed bug

= 1.2 =
Support calculating shipping rates for both Printful and non-Printful products at the same time

= 1.1.2 =
Removed check for Curl extension

= 1.1.1 =
Ignore virtual and downloadable products when calculating shipping rates

= 1.1 =
Added tax rate calculation

= 1.0.2 =
Added option to disable SSL

= 1.0.1 =
Minor improvements

= 1.0 =
First release

== Changelog ==

= 2.0.1 =
* Improved Printful connection status detection
* Improvements for system report
* Show warning if attempting to connect from localhost

= 2.0 =
* New major plugin version
* All new Printful dashboard
* Connect to Printful with a single click
* View your Printful profits and latest Printful product orders in WordPress admin
* Edit your shipping carriers from Printful dashboard
* Improved sales tax compatibility with existing tax rates
* New status page - see if your integration is running smoothly
* New support page - all info about finding help in one place
* Size chart tab - when pushing products from Printful, the size chart will be placed in a separate tab
* Improved logging of API requests coming to and from Printful

= 1.2.8 =
* Fixed bug that caused tax rates to become invisible on checkout since WC 3.0

= 1.2.7 =
* Do not calculate shipping rates for US addresses while ZIP or state is not entered

= 1.2.6 =
* Include shipping rates in tax calculation for states that require that

= 1.2.5 =
* Added option to allow Woocommerce default rates together with Printful rates for Printful products

= 1.2.4 =
* Prevent virtual products from requiring shipping rate when bought together with Printful products

= 1.2.3 =
* Fixed issue introduced in 1.2.2

= 1.2.2 =
* Fixed PHP warning on Woocommerce 2.6 due to changed method signature
* Fixed conflict with "Multiple Packages for WooCommerce" plugin

= 1.2.1 =
* Fixed bug that could have show error message when calculating shipping rates

= 1.2 =
* Support calculating shipping rates for both Printful and non-Printful products at the same time (non-Printful
products will get default rates provided by Woocommerce)
* Added caching to tax rates
* Improved compatibility with Woocommerce 2.6

= 1.1.2 =
* Removed check for Curl extension (since we already used wp_remote_get and it is no longer necessary)

= 1.1.1 =
* Ignore virtual and downloadable products when calculating shipping rates

= 1.1 =
* Added option to calculate sales tax rates for locations where it is required for Printful orders
* Added automatic conversion of shipping rates to the currency used by Woocommerce
* Printful API client library updated to use Wordpress internal wp_remote_get method instead of CURL directly
* Changed plugin code structure for easier implementation of new features in the future

= 1.0.2 =
* Added option to disable SSL for users that do not have a valid CA certificates in their PHP installation

= 1.0.1 =
* Removed CURLOPT_FOLLOWLOCATION that caused problems on some hosting environments
* Added option to display reason status messages if the rate API request has failed

= 1.0 =
* First release
