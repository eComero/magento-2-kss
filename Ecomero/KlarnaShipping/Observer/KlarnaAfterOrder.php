<?php

declare(strict_types=1);
/**
 *           a88888P8
 *          d8'
 * .d8888b. 88        .d8888b. 88d8b.d8b. .d8888b. .dd888b. .d8888b.
 * 88ooood8 88        88'  `88 88'`88'`88 88ooood8 88'    ` 88'  `88
 * 88.  ... Y8.       88.  .88 88  88  88 88.  ... 88       88.  .88
 * `8888P'   Y88888P8 `88888P' dP  dP  dP `8888P'  dP       `88888P'.
 *
 *           Copyright © eComero Management AB, All rights reserved.
 */

namespace Ecomero\KlarnaShipping\Observer;

use Magento\Checkout\Model\Session as CheckoutSession;
use Magento\Framework\Event\ObserverInterface;

class KlarnaAfterOrder implements ObserverInterface
{
    /**
     * @var CheckoutSession
     */
    private CheckoutSession $session;

    public function __construct(CheckoutSession $session)
    {
        $this->session = $session;
    }

    public function execute(\Magento\Framework\Event\Observer $observer)
    {
        $methodName = $this->session->getKssMethod();
        $carrier = $this->session->getKssCarrier();
        $class = $this->session->getKssClass();
        $pickupPointId = $this->session->getKssPickupLocationId();
        $tmsReference = $this->session->getKssTmsReference();

        $order = $observer->getData('order');
        $order->setKssMethod($methodName);
        $order->setKssCarrier($carrier);
        $order->setKssClass($class);
        $order->setKssPickupLocationId($pickupPointId);
        $order->setKssTmsReference($tmsReference);
        $order->save();

        $this->session->unsKssMethod();
        $this->session->unsKssCarrier();
        $this->session->unsKssClass();
        $this->session->unsKssPickupLocationId();
        $this->session->unsKssTmsReference();
    }
}
