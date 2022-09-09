<?php
declare(strict_types=1);

namespace Ecomero\KlarnaShipping\Plugin\Api;

use Magento\Sales\Api\Data\OrderExtensionFactory;
use Magento\Sales\Api\Data\OrderInterface;
use Magento\Sales\Api\Data\OrderSearchResultInterface;
use Magento\Sales\Api\OrderRepositoryInterface;


class OrderRepository
{
    const KSS_METHOD = 'kss_method';
    const KSS_CARRIER = 'kss_carrier';
    const KSS_CLASS = 'kss_class';
    const PICKUP_LOCATION_ID = 'kss_pickup_location_id';
    const KSS_TMS_REFERNCE = 'kss_tms_reference';

    protected $extensionFactory;

    public function __construct(OrderExtensionFactory $extensionFactory)
    {
        $this->extensionFactory = $extensionFactory;
    }

    public function afterGet(OrderRepositoryInterface $subject, OrderInterface $order): OrderInterface
    {
        $kssMethod = $order->getData(self::KSS_METHOD);
        $kssCarrier = $order->getData(self::KSS_CARRIER);
        $kssClass = $order->getData(self::KSS_CLASS);
        $pickupLocationId = $order->getData(self::PICKUP_LOCATION_ID);
        $kssTmsReference = $order->getData(self::KSS_TMS_REFERNCE);
        
        $extensionAttributes = $order->getExtensionAttributes();
        $extensionAttributes = $extensionAttributes ? $extensionAttributes : $this->extensionFactory->create();
        
        $extensionAttributes->setKssMethod($kssMethod);
        $extensionAttributes->setKssCarrier($kssCarrier);
        $extensionAttributes->setKssClass($kssClass);
        $extensionAttributes->setKssPickupLocationId($pickupLocationId);
        $extensionAttributes->setKssTmsReference($kssTmsReference);

        $order->setExtensionAttributes($extensionAttributes);

        return $order;
    }

    public function afterGetList(OrderRepositoryInterface $subject, OrderSearchResultInterface $searchResult): OrderSearchResultInterface
    {
        $orders = $searchResult->getItems();

        foreach ($orders as &$order) {
            $kssMethod = $order->getData(self::KSS_METHOD);
            $kssCarrier = $order->getData(self::KSS_CARRIER);
            $kssClass = $order->getData(self::KSS_CLASS);
            $pickupLocationId = $order->getData(self::PICKUP_LOCATION_ID);
            $kssTmsRefernce = $order->getData(self::KSS_TMS_REFERNCE);
            
            $extensionAttributes = $order->getExtensionAttributes();
            $extensionAttributes = $extensionAttributes ? $extensionAttributes : $this->extensionFactory->create();
            
            $extensionAttributes->setKssMethod($kssMethod);
            $extensionAttributes->setKssCarrier($kssCarrier);
            $extensionAttributes->setKssClass($kssClass);
            $extensionAttributes->setKssPickupLocationId($pickupLocationId);
            $extensionAttributes->setKssTmsReference($kssTmsRefernce);
            
            $order->setExtensionAttributes($extensionAttributes);
        }

        return $searchResult;
    }
}