# GoogleTagManger - 410Gone - Magento 2.4 module

Installation des tag Gtag Manager et création d'events personnalisés sur le dataLayer

## Installation

Copier tout le dossier Gone_GoogleTag dans app/Code
run
```bash
php bin/magento se:up
```

## Usage

Recupérer ou générer un ID [Google Tag Manager](https://tagmanager.google.com/)

Setup Gtag ID Code in Admin > Store Configuration > General > Advanced reporting > Google Tag Manager

## Custom Events

Customs events are added through config.json file

### Classic events

```bash
              {
                'observedClass': '.tocart', //DOM selector
                'observedEvent': 'click',   //Listened event on DOM selector
                'dataLayer': {              //data that will be added to dataLayer
                    'name': 'Add to cart',
                    'label': 'Ajouter au panier',
                    'category': 'Cart',
                    'action': 'Panier',
                    'event': 'add_to_cart'
                }
            }
```

### Events with data context capture #1

In current version, captured data will be set in 'label'

```bash
               {
                'observedClass': '.mp-attachment-tab__item > a',
                'observedEvent': 'click',
                'dataSourceAttr':'title',    //optional key to capture data from attribute of the seleted DOM object
                'dataLayer': {
                    'name': 'DL fichier PDF',
                    'label': 'Nom du PDF',
                    'category': 'Navigation ',
                    'event': 'dl_pdf'
                }
            },
```

### Events with data context capture #2

```bash
{
                'observedClass': '.block.crosssell .tocart',
                'observedEvent': 'click',
                'dataSourceParentForm': {
                    'dataSourceAttr':'data-product-sku'  //optional key to capture data in the seleted DOM object parent form tag (not from form inputs in this version)
                },
                'dataLayer': {
                    'event': 'checkbox_crosssell_product',
                    'name': 'Checkbox Ventes croisées',
                    'label': 'CheckboxVenteCroisee_valeur',
                    'category': 'Cart'
                }
            },
```

## License
Copyright © 410 Gone [contact@410-gone.fr](mailto:contact@410-gone.fr).
All rights reserved.
See LICENSE.txt for license details [http://opensource.org/licenses/osl-3.0.php](http://opensource.org/licenses/osl-3.0.php).
