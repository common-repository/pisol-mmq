=== Product Quantity, Minimum Maximum quantity & Minimum Order quantity WooCommerce ===
Contributors: rajeshsingh520
Donate link: piwebsolution.com
Tags: Minimum Order Amount, Minimum order, WooCommerce minimum order, minimum purchase, minimum, WooCommerce order, Maximum Order Amount, minimum maximum quantity, min max quantity, minimum order size
Requires at least: 3.0.1
Tested up to: 6.7.0
Stable tag: 2.1.72
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Set Product Quantity, Minimum Maximum Quantity, Minimum Order Amount, Minimum order size for WooCommerce

== Description ==

[Try pro version frontend & backend on demo website](https://websitemaintenanceservice.in/mmq_demo)

* Activate the plugin, go to setting page and and set <strong>global level min or max Product Quantity</strong>

* Set <strong>minimum product quantity</strong> for each product that should be purchased

* Set <strong>maximum product quantity</strong> for each product that can be purchased

* Set minimum cart total that is minimum total at the time of checkout

* If you don't want quantity restriction on certain product you can <strong>disable the limit</strong> on that particular product quantity

* Enable <strong>minimum amount restriction</strong> on the cart total

* Exclude a product from global minimum amount restriction, in Free version you can exclude 2 product only 

* Set <strong>different messages</strong> of the minimum amount restriction based on the cart total as percent of the minimum amount set in the plugin setting

* Show <strong>minimum amount progress</strong> in circular progress bar

* Show <strong>warning message of minimum amount</strong> on top of the pages 

== PRO Version ==

[Try pro version front & back end on demo website](https://websitemaintenanceservice.in/mmq_demo)

* All the features of Free version

* Set a <strong>different minimum quantity for each product</strong>, this will overwrite the global minimum quantity for that product 

* Set a <strong>different minimum quantity for each variation</strong>

* Set a <strong>different maximum quantity for each product</strong>, this will overwrite the global maximum quantity for that product 

* Set a <strong>different maximum quantity for each variation</strong>

* Set a <strong>different quantity multiple for each variation</strong>

* <strong>Customize messages</strong>

* Change the image that is shown inside the circular progress for the minimum order amount WooCommerce

* <strong>Set text and background color of the message box</strong> based on the different condition the quantity of product present in the cart

* Minimum order amount WooCommerce bar get <strong>updated by ajax</strong>

* Place minimum order amount needed info bar using short code **[pisol_mmq_notification]**

* Pro version update the <strong>min max notice message on the category / Shot page with ajax</strong>

* Control the page where you want to show the minimum order amount restriction notification bar

* You can write custom code to disable minimum order amount notification on custom pages using filter function

* Show/Hide Min Max quantity message on product archive page, product page, cart page or checkout page

* Set **different position** for Min Max quantity message on product archie page and single product page

* **Don't go inside each product to set Min/Max quantity**, instead  set it from the category, so all product withing this category will inherit this min/max quantity limit

* Set **minimum quantity restriction on the category**, so user has to purchase minimum that many unit from that particular category

* Checkout page will redirect to cart page when the **Minimum quantity restriction** is not fulfilled for any product

* Checkout page will **redirect to cart page** when the Minimum amount restriction is not fulfilled for any product

* You can **set minimum amount restriction per category basis**, so if a buyer purchase a $10 product from Category A, and you have set Min amount limit of $20 on that category then he wont be able to checkout until he purchase $10 more from the same category A

* You can apply category level min amount restriction even on the sub category products as well

* Force produce to be **ordered in a multiple of X units**, E.g: you can enable this option on a product and make it to be ordered in a group of 4 unit, then user can order 4, 8, 12, 16 units of that product if they try to order say 3 units they will get and error and will not be allowed to checkout 
[Example of product to be ordered in multiple of 4](http://websitemaintenanceservice.in/mmq/product/polo/)

* Force category product quantity to be **ordered in a multiple of X unit**, E.g: Category A : you have set quantity to be in multiple of 3 unit, when user will add 2 unit of product xA he wont be allow to checkout as it is not multiple of 3, now if he adds 1 unit of yA product in the cart then his total quantity from Cat A equals to 3 unit which is multiple of 3 so he can checkout 

* Exclude product from min quantity restriction on the category, the quantity of this excluded product will not be counted for restriction. E.g: Cat A min quantity restriction is 3 and customer added product AA and AB 1 unit each and product AB is excluded product then the customer has to purchase 2 unit more the unit of AB will not count in.

* Exclude product amount from min amount restriction on the category, E.g: Cat A min amount restriction is 20$, customer added product AA and AB in cart both of 10$ each then customer will still need to buy $10 more as the AB (being the excluded product it wont be counted for restriction)

== Screenshots ==
1. Running the minimum required amount for checkout, and its notification is shown on top using short code [pisol_mmq_notification]
2. Message shown below the product in shop page, this info gets updated with ajax as user add product to cart
3. This shows the product status on the cart page like how much he can buy more or he need to buy more

== Frequently Asked Questions ==

= I only want to set Min quantity =
Yes you can set only minimum product quantity on global level

= I only want to set Maximum Quantity =
Yes you can set maximum product quantity on global level

= I want to set min max quantity, but exclude some product =
yes there is option to exclude product from global min amd max quantity

= Can i change the message that is shown =
Yes, you can set different messages 

= Where will it show the message =
It will show the message in top bar notification

= Will the message update on through Ajax =
Yes as the user buys the message on the top bar updates itself

= Can i set custom color for the message box =
yes in the pro version you can set the color for the message box based on different conditions

= Show linear progress towards minimum quantity required for product =
Pro version show a linear progress bar for the minimum quantity required

= Change color of the progress live =
Yes you can change color of the line in the pro version from the backend

= Change the progress message on the Archive pages =
Pro version does change the progress message on the archive page with ajax

= I don't want to show the minimum amount restriction on all the pages of the site =
Pro version does gives you the option to control the page where you want to show the minimum amount restriction

= I have some custom pages where i don't want to show the minimum amount restriction =
In Pro version there is a filter available, using that filter you can write your custom logic to disable the minimum amount notification bar
 You can use filter <strong>pi_mmq_control_filter</strong> to create your custom rule to disable the bar
            <br>e.g: add_filter("pi_mmq_control_filter", function($val) { return true; } ); <br>true will enable the amount bar
            <br>false will disable it

= I don't wan to show the min max quantity message on the show or category page, only want to show the message on single product page =
You can fo that in the pro version, you can disable it for product archive pages and only enable it for the single product page

= Can i set the Min/Max quantity limit from the category =
Yes, you can set it from the category, and all the product withing that category will inherit the min/max quantity limit from the parent category

= Can add product exception as i don't want all the product in category to has quantity limit =
Yes, you can select the product that you don't want to inherit the limit set in the category. or if you wan you can directly add a different min/max quantity limit for those product from the respective product

= I want customer to buy at-least 3 unit from a specific category =
You can set minimum quantity restriction on the category level in the pro version, so customer has to buy 3 unit, this 3 unit can be from one single product or it can be 1 unit each from 3 different product of the same category

= I don't want user to go to checkout page when Min quantity / Min Amount restriction are not fulfilled =
In the pro version checkout page will redirect the user to cart page when the min quantity restriction or min amount restriction is not fulfilled

= I want to put minimum purchase amount restriction on category level =
Yes you can add, that using the pro version 

= I want to put min purchase amount restriction on category level but exclude certain product from this restriction =
Yes you can do that in the pro version it allows you to exclude certain product from category level restriction

= I want to apply min amount restriction on the category level and also include its child category product in that restriction =
Yes you can do that in the pro version, it has the option using that you can apply the rule on sub category product as well , when this option is enabled it will apply the min amount restriction on the product that belongs to its child categories 

= I want to make the a product to be ordered in a group/multiple of 2 units that is user can only order 2, 4, 6, 8 units of this products =
Yes you can do that in the Pro version, it allow you to set this restriction on a product then user will only be able to order this product in multiple of 2 only

= I want to exclude some product from minimum amount global restriction =
In free version you can only exclude maximum 2 product, in Pro version you can exclude unlimited products

= We want to restrict the quantity ordered from a category to be in multiple of X units =
yes you can do that in the pro version, E.g: Category A : you have set quantity to be in multiple of 3 unit, when user will add 2 unit of product xA he wont be allow to checkout as it is not multiple of 3, now if he adds 1 unit of yA product in the cart then his total quantity from Cat A equals to 3 unit which is multiple of 3 so he can checkout 

= I want to set a different min quantity restriction for each variation in variable product =
You can do that in the PRO version, it allows you to set different min quantity for each variation

= I want to set a different max quantity restriction for each variation in variable product =
You can do that in the PRO version, it allows you to set different max quantity for each variation

= I want to set a different quantity multiple restriction for each variation in variable product =
You can do that in the PRO version, it allows you to set different quantity multiple for each variation

= Is it HPOS compatible =
Yes the Free version and PRO version both are HPOS compatible

== Upgrade Notice ==

= 2.1.72 =
* Tested for WP 6.7.0

= 2.1.71 =
* Tested for WC 9.3.3

= 2.1.66 =
* Tested for WC 9.2.0

= 2.1.64 =
* Tested for WC 9.1.4

= v2.1.63 =
* Tested for WC 9.1.0

= v2.1.62 =
* Tested for WP 6.6.1

= v2.1.61 =
* option to disable Min amount bar 
* option to disable Min amount circular progress bar