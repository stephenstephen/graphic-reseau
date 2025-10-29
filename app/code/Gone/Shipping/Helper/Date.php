<?php
/*
 * Copyright © 410 Gone (contact@410-gone.fr). All rights reserved.
 * See LICENSE.txt for license details (http://opensource.org/licenses/osl-3.0.php).
 */

namespace Gone\Shipping\Helper;

use DateTime;
use Gone\Base\Helper\CoreConfigData;
use Magento\Catalog\Api\Data\ProductInterface;
use Magento\CatalogInventory\Api\StockRegistryInterface;
use Magento\CatalogInventory\Model\Stock\StockItemRepository;
use Magento\Framework\App\Helper\AbstractHelper;
use Magento\Framework\App\Helper\Context;
use Magento\Framework\Stdlib\DateTime\TimezoneInterface;

class Date extends AbstractHelper
{

    public const XML_PATH_MAX_SHIPPING_HOUR = 'gr_config/shipping/max_hour';

    protected TimezoneInterface $_timezone;
    protected StockItemRepository $_stockItemRepository;
    protected CoreConfigData $_configHelper;
    protected StockRegistryInterface $_stockRegistry; // deprecated but only that give real stock...

    // store data
    protected int $_maxHour;

    public function __construct(
        Context                $context,
        TimezoneInterface      $timezone,
        StockItemRepository    $stockItemRepository,
        CoreConfigData         $configHelper,
        StockRegistryInterface $stockRegistry
    )
    {
        parent::__construct($context);
        $this->_timezone = $timezone;
        $this->_stockItemRepository = $stockItemRepository;
        $this->_configHelper = $configHelper;
        $this->_stockRegistry = $stockRegistry;
    }

    public function getMaxProductDelay($quote)
    {
        $maxDelayProduct = null;
        /** @var \Magento\Quote\Model\Quote\Item $quoteItem */
        foreach ($quote->getItems() as $quoteItem) {
            $product = $quoteItem->getProduct();

            $isInStock = $this->_stockRegistry->getStockItem($product->getId())->getQty() >= $quoteItem->getQty();
            if (!$isInStock) {
                $supplyDelay = $product->getSupplyDelays();
                if (!empty($supplyDelay)) {
                    if (!isset($maxDelayProduct) || $maxDelayProduct < $supplyDelay) {
                        $maxDelayProduct = $supplyDelay;
                    }
                }
            }
        }

        return $maxDelayProduct;
    }

    /**
     * @param \Magento\Sales\Api\Data\OrderInterface $order
     * @return |null
     */
    public function getMaxProductDelayOrder($order)
    {
        $maxDelayProduct = null;
        /** @var \Magento\Sales\Model\Order\Item $orderItem */
        foreach ($order->getItems() as $orderItem) {
            $product = $orderItem->getProduct();

            $isInStock = $this->_stockRegistry->getStockItem($product->getId())->getQty() >= $orderItem->getQtyOrdered();
            if (!$isInStock) {
                $supplyDelay = $product->getSupplyDelays();
                if (!empty($supplyDelay)) {
                    if (!isset($maxDelayProduct) || $maxDelayProduct < $supplyDelay) {
                        $maxDelayProduct = $supplyDelay;
                    }
                }
            }
        }

        return $maxDelayProduct;
    }

    public function getProductShippingInfo(ProductInterface $product): string
    {
        if ($product->isAvailable() && $product->isSaleable() && !$product->getOutsized() && !$product->getDangerousProduct()) {
            $timezoneDate = $this->_timezone->date();
            $currentDateFull = $timezoneDate->format('Y-m-d');
            $currentHour = $timezoneDate->format('H');
            $dateTimeNowFull = new DateTime($currentDateFull);

            $orderDay = '';
            // case no shipment today
            if ($currentHour >= $this->_getMaxHour() || $this->_isCurrentDayOff($currentDateFull)) {
                $orderDay = __(date('l', strtotime($this->_getFutureBusinessDay(1))));
            }

            $shippingDate = $this->getShippingDate(1);
            $dateTimeReceiveDate = new DateTime($shippingDate);
            $numberDayBetweenDates = $dateTimeReceiveDate->diff($dateTimeNowFull)->format('%a');

            $deliveryDay = __(date('l', strtotime($shippingDate)));
            if ($numberDayBetweenDates == 1) {
                $deliveryDay = __('tomorrow');
            }

            if (empty($orderDay)) {
                return __(
                    'Shipped today and delivered home %1 by Chronopost if order before %2H00',
                    $deliveryDay,
                    $this->_getMaxHour()
                );
            }
            return __(
                'Delivered home %1 by Chronopost if order before %2 %3H00',
                $deliveryDay,
                $orderDay,
                $this->_getMaxHour()
            );
        }

        return '';
    }

    protected function _getMaxHour(): int
    {
        if (!isset($this->_maxHour)) {
            $this->_maxHour = (int)$this->_configHelper->getValueFromCoreConfig(self::XML_PATH_MAX_SHIPPING_HOUR);
        }

        return $this->_maxHour;
    }

    protected function _isCurrentDayOff($date)
    {
        $timezoneDate = $this->_timezone->date();
        $currentYear = (int)$timezoneDate->format('Y');
        $nextYear = $currentYear + 1;
        $weekDay = date('w', strtotime($date));
        $holidayDates = array_merge($this->_getHolidays($currentYear), $this->_getHolidays($nextYear));

        if (in_array($date, $holidayDates)) {
            $isCurrentDayOff = true;
        } else {
            $isCurrentDayOff = ($weekDay == 0 || $weekDay == 6); // not working all weekend
        }

        return $isCurrentDayOff;
    }

    /**
     * Return an array of holidays date for france in format Y-m-d
     * @param null $year
     * @return array
     */
    protected function _getHolidays($year = null)
    {
        if ($year === null) {
            $year = (int)date('Y');
        }

        $easterDate = easter_date($year);
        $easterDay = date('j', $easterDate);
        $easterMonth = date('n', $easterDate);
        $easterYear = date('Y', $easterDate);

        $holidays = [
            // These days have a fixed date
            $year . '-01-01',  // 1er janvier
            $year . '-05-01',  // Fête du travail / premier mai
            $year . '-05-08',  // Victoire des alliés / 8 mai
            $year . '-07-14',  // Fête nationale / 14 juillet
            $year . '-08-15',  // Assomption
            $year . '-11-01',  // Toussaint / 1er novembre
            $year . '-11-11',  // Armistice / 11 novembre
            $year . '-12-25',  // Noel / 25 decembre

            // These days have a date depending on easter
            date('Y-m-d', mktime(0, 0, 0, $easterMonth, $easterDay + 1, $easterYear)), // Lundi de Pâques
            date('Y-m-d', mktime(0, 0, 0, $easterMonth, $easterDay + 39, $easterYear)), // Jeudi de l'Ascension
            date('Y-m-d', mktime(0, 0, 0, $easterMonth, $easterDay + 50, $easterYear)), // Lundi de Pentecôte
        ];

        sort($holidays);

        return $holidays;
    }

    /**
     * Return the next business day in X days excluding weekend and holidays
     * @param $nbDaysToAdd
     * @return false|string
     */
    protected function _getFutureBusinessDay($nbDaysToAdd)
    {
        $countDays = 0;
        $timezoneDate = $this->_timezone->date();
        $currentDate = $timezoneDate->format('Y-m-d');
        $currentYear = (int)$timezoneDate->format('Y');

        $nextYear = $currentYear + 1;
        $temp = strtotime($currentDate);

        $holidayDates = array_merge($this->_getHolidays($currentYear), $this->_getHolidays($nextYear));

        while ($countDays < $nbDaysToAdd) {
            $nextWeekday = strtotime('+1 weekday', $temp);
            $nextWeekdayDate = date('Y-m-d', $nextWeekday);
            if (!in_array($nextWeekdayDate, $holidayDates)) {
                $countDays++;
            }
            $temp = $nextWeekday;
        }

        return date('Y-m-d', $temp);
    }

    public function getShippingDate(int $nbDayToAdd, $requestFormat = null): string
    {
        $timezoneDate = $this->_timezone->date();
        $currentHour = $timezoneDate->format('H');
        $currentDateFull = $timezoneDate->format('Y-m-d');

        $beforeTime = true;
        if ($currentHour >= $this->_getMaxHour()) {
            $nbDayToAdd++;
            $beforeTime = false;
        }

        if ($this->_isCurrentDayOff($currentDateFull) && $beforeTime) {
            $nbDayToAdd++;
        }

        $receiveDate = $this->_getFutureBusinessDay($nbDayToAdd);

        if (empty($requestFormat)) {
            return $receiveDate;
        }

        return date($requestFormat, strtotime($receiveDate));
    }
}
