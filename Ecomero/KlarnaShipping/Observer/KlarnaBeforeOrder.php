<?php declare(strict_types=1);
/**
 *
 *           a88888P8
 *          d8'
 * .d8888b. 88        .d8888b. 88d8b.d8b. .d8888b. .dd888b. .d8888b.
 * 88ooood8 88        88'  `88 88'`88'`88 88ooood8 88'    ` 88'  `88
 * 88.  ... Y8.       88.  .88 88  88  88 88.  ... 88       88.  .88
 * `8888P'   Y88888P8 `88888P' dP  dP  dP `8888P'  dP       `88888P'
 *
 *           Copyright © eComero Management AB, All rights reserved.
 *
 */
namespace Ecomero\KlarnaShipping\Observer;

use Magento\Framework\Event\ObserverInterface;

class KlarnaBeforeOrder implements ObserverInterface
{
    public function execute(\Magento\Framework\Event\Observer $observer)
    {
        $checkoutData = $observer->getData('checkout');
        $pickupPointId = '';
        $methodName = $checkoutData->getSelectedShippingOption()['name'];
        $carrier = $checkoutData->getSelectedShippingOption()['delivery_details']['carrier'];
        $class = $checkoutData->getSelectedShippingOption()['delivery_details']['class'];
        if ($checkoutData->getSelectedShippingOption()['shipping_method'] === 'PickUpPoint') {
            $pickupPointId = $checkoutData->getSelectedShippingOption()['delivery_details']['pickup_location']['id'];
        }

        $quote = $observer->getData('quote');
        $quote->setKssMethod($methodName);
        $quote->setKssCarrier($carrier);
        $quote->setKssClass($class);
        $quote->setKssPickupLocationId($pickupPointId);
    }
}
