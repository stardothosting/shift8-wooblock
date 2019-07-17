=== Shift8 Woocommerce Postal Blocker ===
* Contributors: shift8
* Donate link: https://www.shift8web.ca
* Tags: woocommerce, wordpress, block, blacklist, woocommerce blacklist, postal, zip, postal code, zip code, zipcode, postalcode
* Requires at least: 3.0.1
* Tested up to: 5.2.2
* Stable tag: 1.04
* License: GPLv3
* License URI: http://www.gnu.org/licenses/gpl-3.0.html

Plugin that allows you to input a list of postal / zip codes to block from ordering on your Woocommerce site. You can determine if you want to hide a payment gateway (i.e. credit card) or an outright block. You can now select multiple payment gateways to "remove" if the zip or postal code matches during the checkout process. An encrypted browser cookie is set that expires on a defined date, set by you in the admin settings. This means that you can "Ban" specific payment gateways for any number of days if the end-user's postal or zip code matches.

== Want to see the plugin in action? ==

You can view three example sites where this plugin is live :

- Example Site 1 : [Wordpress Hosting](https://www.stackstar.com "Wordpress Hosting")
- Example Site 2 : [Web Design in Toronto](https://www.shift8web.ca "Web Design in Toronto")

== Features ==

- Settings area to allow you to define the postal codes in a text box. Postal / zip codes should be one per line.

== Installation ==

This section describes how to install the plugin and get it working.

e.g.

1. Upload the plugin files to the `/wp-content/plugins/shif8-wooblock` directory, or install the plugin through the WordPress plugins screen directly.
2. Activate the plugin through the 'Plugins' screen in WordPress
3. Navigate to the plugin settings page and define your settings

== Frequently Asked Questions ==

= I tested it on myself and its not working for me! =

You should add a postal code (dont worry about spaces like L0L 0L0 versus L0L0L0, or case sensitivity). Then in a new browser (not logged in as admin) try to checkout with a blocked postal code.

== Screenshots ==

1. Admin area

== Changelog ==

= 1.00 =
* Stable version created

= 1.01 =
* Fixed bug in pattern matching portion of postal code array

= 1.02 =
* Added ability to set number of days for the cookie to ban payment gatways. Default is 30 if none is entered.
* Added ability to select multiple payment gateways to remove if a postal or zip code matches the list

= 1.03 =
* Loading admin js file correctly now

= 1.04 =
* Wordpress 5 compatibility
