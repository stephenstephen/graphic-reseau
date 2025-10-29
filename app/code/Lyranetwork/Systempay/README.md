# Systempay for Magento 2

Systempay for Magento 2 is an open source plugin that links e-commerce websites based on Magento to Systempay secure payment gateway developed by [Lyra Network](https://www.lyra.com/).

Namely, it enables the following payment methods:
* Systempay - Standard payment
* [mutli] Systempay - Payment in installments
* [gift] Systempay - Gift card payment
* [choozeo] Systempay - Choozeo payment
* [oney] Systempay - Payment in 3 or 4 times Oney
* [fullcb] Systempay - Full CB payment
* [franfinance] Systempay - FranFinance payment
* [sepa] Systempay - SEPA payment
* [paypal] Systempay - PayPal payment
* [other] Systempay - Other payment means

## Installation & upgrade

- Remove app/code/Lyranetwork/Systempay folder if already exists.
- Create a new app/code/Lyranetwork/Systempay folder.
- Unzip module in your Magento 2 app/code/Lyranetwork/Systempay folder.
- Open command line and change to Magento installation root directory.
- Enable module: php bin/magento module:enable --clear-static-content Lyranetwork_Systempay
- Upgrade database: php bin/magento setup:upgrade
- Re-run compile command: php bin/magento setup:di:compile
- Update static files by: php bin/magento setup:static-content:deploy [locale]

In order to deactivate the module: php bin/magento module:disable --clear-static-content Lyranetwork_Systempay

## Configuration

- In Magento 2 administration interface, browse to "STORES > Configuration" menu.
- Click on "Payment Methods" link under the "SALES" section.
- Expand Systempay payment method to enter your gateway credentials.
- Refresh invalidated Magento cache after config saved.

## License

Each Systempay payment module source file included in this distribution is licensed under the Open Software License (OSL 3.0).

Please see LICENSE.txt for the full text of the OSL 3.0 license. It is also available through the world-wide-web at this URL: https://opensource.org/licenses/osl-3.0.php.