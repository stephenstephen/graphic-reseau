<?php
/**
 * 2007-2021 Atoo Next
 *
 * --------------------------------------------------------------------------------
 *  /!\ Ne peut être utilisé en dehors du programme Atoo-Sync GesCom /!\
 * --------------------------------------------------------------------------------
 *
 *  Ce fichier fait partie du logiciel Atoo-Sync .
 *  Vous n'êtes pas autorisé à le modifier, à le recopier, à le vendre ou le redistribuer.
 *  Cet en-tête ne doit pas être retiré.
 *
 * @author    Atoo Next SARL (contact@atoo-next.net)
 * @copyright 2009-2021 Atoo Next SARL
 * @license   Commercial
 * @script    atoosync-gescom-webservice.php
 *
 * --------------------------------------------------------------------------------
 *  /!\ Ne peut être utilisé en dehors du programme Atoo-Sync GesCom /!\
 * --------------------------------------------------------------------------------
 */

namespace AtooNext\AtooSync\Cms\Configuration;

/**
 * Class CmsConfiguration
 */
class CmsConfiguration
{
    /** @var CmsConfigurationLanguage[]   La liste des langues */
    public $languages;

    /** @var CmsConfigurationPayment[]   La liste des modes de paiement */
    public $payments;

    /** @var CmsConfigurationOrderStatus[]   La liste des statuts des commandes */
    public $orderStatuses;

    /** @var CmsConfigurationOrderUpdateStatus[]   La liste des statuts des commandes pour la mise à jour dans le CMS */
    public $orderUpdateStatuses;

    /** @var CmsConfigurationTaxRule[]   La liste des taxes des articles */
    public $taxRules;

    /** @var CmsConfigurationTax[]   La liste des taxes (sur les commandes) */
    public $taxes;

    /** @var CmsConfigurationZone[]   La liste des zones */
    public $zones;

    /** @var CmsConfigurationManufacturer[]   La liste des fabricants */
    public $manufacturers;

    /** @var CmsConfigurationSupplier[]   La liste des fournisseurs */
    public $suppliers;

    /** @var CmsConfigurationCarrier[]   La liste des transporteurs */
    public $carriers;

    /** @var CmsConfigurationCurrency[]   La liste des devises */
    public $currencies;

    /** @var CmsConfigurationCountry[]   La liste des pays */
    public $countries;

    /** @var CmsConfigurationShop[]   La liste des boutiques */
    public $shops;

    /** @var CmsConfigurationProductFeature[]   La liste des caractéristiques des articles */
    public $productFeatures;

    /** @var CmsConfigurationQuoteStatus[]   La liste des statuts des devis */
    public $quoteStatuses;

    /** @var CmsConfigurationQuoteUpdateStatus[]   La liste des statuts des devis pour la mise à jour dans le CMS */
    public $quoteUpdateStatuses;

    /**
     * ATSCConfiguration constructor.
     */
    public function __construct()
    {
        $this->languages = array();
        $this->payments = array();
        $this->orderStatuses = array();
        $this->orderUpdateStatuses = array();
        $this->taxRules = array();
        $this->taxes = array();
        $this->zones = array();
        $this->manufacturers = array();
        $this->suppliers = array();
        $this->carriers = array();
        $this->currencies = array();
        $this->countries = array();
        $this->shops = array();
        $this->productFeatures = array();
        $this->quoteStatuses = array();
        $this->quoteUpdateStatuses = array();
    }

    /**
     * Formate l'objet en XML
     *
     * @return string Le XML de la configuration
     */
    public function getXML()
    {
        $xml = '<?xml version="1.0" encoding="UTF-8"?>';
        $xml .= '<config>';

        /**
         *  Les langues
         */
        $xml .= '<languages>';
        if (count($this->languages) > 0) {
            foreach ($this->languages as $language) {
                $xml .= $language->getXML();
            }
        }
        $xml .= '</languages>';

        /**
         *  Les modes de paiement
         */
        $xml .= '<payments>';
        if (count($this->payments) > 0) {
            foreach ($this->payments as $payment) {
                $xml .= $payment->getXML();
            }
        }
        $xml .= '</payments>';

        /**
         *  Les statuts des commandes
         */
        $xml .= '<order_statuses>';
        if (count($this->orderStatuses) > 0) {
            foreach ($this->orderStatuses as $orderStatus) {
                $xml .= $orderStatus->getXML();
            }
        }
        $xml .= '</order_statuses>';

        /**
         *  Les statuts des commandes pour la mise à jour dans le CMS
         */
        $xml .= '<order_update_statuses>';
        if (count($this->orderUpdateStatuses) > 0) {
            foreach ($this->orderUpdateStatuses as $orderStatus) {
                $xml .= $orderStatus->getXML();
            }
        }
        $xml .= '</order_update_statuses>';

        /**
         *  Les statuts des devis
         */
        $xml .= '<quote_statuses>';
        if (count($this->quoteStatuses) > 0) {
            foreach ($this->quoteStatuses as $quoteStatus) {
                $xml .= $quoteStatus->getXML();
            }
        }
        $xml .= '</quote_statuses>';

        /**
         *  Les statuts des devis pour la mise à jour dans le CMS
         */
        $xml .= '<quote_update_statuses>';
        if (count($this->quoteUpdateStatuses) > 0) {
            foreach ($this->quoteUpdateStatuses as $orderStatus) {
                $xml .= $orderStatus->getXML();
            }
        }
        $xml .= '</quote_update_statuses>';

        /**
         *  les régles de taxes (sur les articles)
         */
        $xml .= '<tax_rules>';
        if (count($this->taxRules) > 0) {
            foreach ($this->taxRules as $taxRule) {
                $xml .= $taxRule->getXML();
            }
        }
        $xml .= '</tax_rules>';

        /**
         *  les taxes (sur les lignes des commandes)
         */
        $xml .= '<taxes>';
        if (count($this->taxes) > 0) {
            foreach ($this->taxes as $tax) {
                $xml .= $tax->getXML();
            }
        }
        $xml .= '</taxes>';

        /**
         *  les zones de pays
         */
        $xml .= '<zones>';
        if (count($this->zones) > 0) {
            foreach ($this->zones as $zone) {
                $xml .= $zone->getXML();
            }
        }
        $xml .= '</zones>';

        /**
         *  les fabricants
         */
        $xml .= '<manufacturers>';
        if (count($this->manufacturers) > 0) {
            foreach ($this->manufacturers as $manufacturer) {
                $xml .= $manufacturer->getXML();
            }
        }
        $xml .= '</manufacturers>';

        /**
         *  les fournisseurs
         */
        $xml .= '<suppliers>';
        if (count($this->suppliers) > 0) {
            foreach ($this->suppliers as $supplier) {
                $xml .= $supplier->getXML();
            }
        }
        $xml .= '</suppliers>';

        /**
         *  les transporteurs
         */
        $xml .= '<carriers>';
        if (count($this->carriers) > 0) {
            foreach ($this->carriers as $carrier) {
                $xml .= $carrier->getXML();
            }
        }
        $xml .= '</carriers>';

        /**
         *  les devises
         */
        $xml .= '<currencies>';
        if (count($this->currencies) > 0) {
            foreach ($this->currencies as $currency) {
                $xml .= $currency->getXML();
            }
        }
        $xml .= '</currencies>';

        /**
         *  les pays
         */
        $xml .= '<countries>';
        if (count($this->countries) > 0) {
            foreach ($this->countries as $country) {
                $xml .= $country->getXML();
            }
        }
        $xml .= '</countries>';

        /**
         *  les boutiques
         */
        $xml .= '<shops>';
        if (count($this->shops) > 0) {
            foreach ($this->shops as $shop) {
                $xml .= $shop->getXML();
            }
        }
        $xml .= '</shops>';

        /**
         *  les caractéristiques des articles
         */
        $xml .= '<product_features>';
        if (count($this->productFeatures) > 0) {
            foreach ($this->productFeatures as $productFeature) {
                $xml .= $productFeature->getXML();
            }
        }
        $xml .= '</product_features>';


        $xml .= '</config>';
        return $xml;
    }

    /**
     * Créé un objet CmsConfiguration à partir du XML envoyé par l'application Atoo-Sync
     *
     * @param \SimpleXMLElement $configurationXml L'objet XML envoyé par l'application Atoo-Sync
     * @return CmsConfiguration
     */
    public static function createFromXML(\SimpleXMLElement $configurationXml)
    {
        $CmsConfiguration = new CmsConfiguration();
        if ($configurationXml) {
            // les Langues de la boutique
            if ($configurationXml->languages) {
                $CmsConfiguration->languages = array();
                foreach ($configurationXml->languages->language as $language) {
                    $CmsConfiguration->languages[] = CmsConfigurationLanguage::createFromXML($language);
                }
            }
            if ($configurationXml->payments) {
                $CmsConfiguration->payments = array();
                foreach ($configurationXml->payments->payment as $payment) {
                    $CmsConfiguration->payments[] = CmsConfigurationPayment::createFromXML($payment);
                }
            }
            if ($configurationXml->orderStatuses) {
                $CmsConfiguration->orderStatuses = array();
                foreach ($configurationXml->orderStatutes->orderStatus as $orderStatus) {
                    $CmsConfiguration->orderStatuses[] = CmsConfigurationOrderStatus::createFromXML($orderStatus);
                }
            }
            if ($configurationXml->orderUpdateStatuses) {
                $CmsConfiguration->orderUpdateStatuses = array();
                foreach ($configurationXml->orderUpdateStatutes->orderUpdateStatus as $orderUpdateStatus) {
                    $CmsConfiguration->orderUpdateStatuses[] = CmsConfigurationOrderUpdateStatus::createFromXML($orderUpdateStatus);
                }
            }
            if ($configurationXml->quoteStatus) {
                $CmsConfiguration->quoteStatuses = array();
                foreach ($configurationXml->quoteStatuses->quoteStatus as $quoteStatus) {
                    $CmsConfiguration->quoteStatuses[] = CmsConfigurationQuoteStatus::createFromXML($quoteStatus);
                }
            }
            if ($configurationXml->quoteUpdateStatuses) {
                $CmsConfiguration->quoteUpdateStatuses = array();
                foreach ($configurationXml->quoteUpdateStatutes->quoteUpdateStatus as $quoteUpdateStatus) {
                    $CmsConfiguration->quoteUpdateStatuses[] = CmsConfigurationQuoteUpdateStatus::createFromXML($quoteUpdateStatus);
                }
            }
            if ($configurationXml->taxRules) {
                $CmsConfiguration->taxRules = array();
                foreach ($configurationXml->taxRules->taxRule as $taxRule) {
                    $CmsConfiguration->taxRules[] = CmsConfigurationTaxRule::createFromXML($taxRule);
                }
            }
            if ($configurationXml->taxes) {
                $CmsConfiguration->taxes = array();
                foreach ($configurationXml->taxes->tax as $tax) {
                    $CmsConfiguration->taxes[] = CmsConfigurationTax::createFromXML($tax);
                }
            }
            if ($configurationXml->zones) {
                $CmsConfiguration->zones = array();
                foreach ($configurationXml->zones->zone as $zone) {
                    $CmsConfiguration->zones[] = CmsConfigurationZone::createFromXML($zone);
                }
            }
            if ($configurationXml->manufacturers) {
                $CmsConfiguration->manufacturers = array();
                foreach ($configurationXml->manufacturers->manufacturer as $manufacturer) {
                    $CmsConfiguration->manufacturers[] = CmsConfigurationManufacturer::createFromXML($manufacturer);
                }
            }
            if ($configurationXml->suppliers) {
                $CmsConfiguration->suppliers = array();
                foreach ($configurationXml->suppliers->supplier as $supplier) {
                    $CmsConfiguration->suppliers[] = CmsConfigurationSupplier::createFromXML($supplier);
                }
            }
            if ($configurationXml->carriers) {
                $CmsConfiguration->carriers = array();
                foreach ($configurationXml->carriers->carrier as $carrier) {
                    $CmsConfiguration->carriers[] = CmsConfigurationCarrier::createFromXML($carrier);
                }
            }
            if ($configurationXml->currencies) {
                $CmsConfiguration->currencies = array();
                foreach ($configurationXml->currencies->currency as $currency) {
                    $CmsConfiguration->currencies[] = CmsConfigurationCurrency::createFromXML($currency);
                }
            }
            if ($configurationXml->countries) {
                $CmsConfiguration->countries = array();
                foreach ($configurationXml->countries->country as $country) {
                    $CmsConfiguration->countries[] = CmsConfigurationCountry::createFromXML($country);
                }
            }
            if ($configurationXml->shops) {
                $CmsConfiguration->shops = array();
                foreach ($configurationXml->shops->shop as $shop) {
                    $CmsConfiguration->shops[] = CmsConfigurationShop::createFromXML($shop);
                }
            }
            if ($configurationXml->productFeatures) {
                $CmsConfiguration->productFeatures = array();
                foreach ($configurationXml->productFeatures->productFeature as $productFeature) {
                    $CmsConfiguration->productFeatures[] = CmsConfigurationProductFeature::createFromXML($productFeature);
                }
            }
        }
        return $CmsConfiguration;
    }
}
