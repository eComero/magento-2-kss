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

use Magento\Checkout\Model\Session as CheckoutSession;
use Magento\Framework\Event\ObserverInterface;

class KlarnaBeforeOrder implements ObserverInterface
{
    protected $session;

    public function __construct(CheckoutSession $session)
    {
        $this->session = $session;
    }

    public function execute(\Magento\Framework\Event\Observer $observer)
    {
        $checkoutData = $observer->getData('checkout');
        $selectedOptions = $checkoutData->getSelectedShippingOption();

        if ($selectedOptions) {
            if (array_key_exists('name', $selectedOptions)) {
                $this->session->setKssMethod($selectedOptions['name']);
            }

            if (array_key_exists('delivery_details', $selectedOptions)) {
                if (array_key_exists('carrier', $selectedOptions['delivery_details'])) {
                    $this->session->setKssCarrier($selectedOptions['delivery_details']['carrier']);
                }

                if (array_key_exists('class', $selectedOptions['delivery_details'])) {
                    $this->session->setKssClass($selectedOptions['delivery_details']['class']);
                }

                if (array_key_exists('shipping_method', $selectedOptions)) {
                    if ($selectedOptions['shipping_method'] === 'PickUpPoint') {
                        $pickupPointId = $selectedOptions['delivery_details']['pickup_location']['id'];
                        $this->session->setKssPickupLocationId($pickupPointId);
                        
                        // Klarna is using pickpoint address as shipping address, this is not correct
                        // The shipping address should be the customers home address,
                        // The Pickup Location is used by the carrier to figure out the real shipping address
                        $billingAddress = $checkoutData->getBillingAddress();
                        $quote->getShippingAddress()->setStreet([0 => $billingAddress['street_address']]);
                        $quote->getShippingAddress()->setPostcode($billingAddress['postal_code']);
                        $quote->getShippingAddress()->setCity($billingAddress['city']);
                    }
                }
            }
        }
    }
}
