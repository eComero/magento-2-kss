<?php
declare(strict_types=1);

namespace Ecomero\KlarnaShipping\ViewModel;

use Magento\Framework\App\RequestInterface;
use Magento\Framework\View\Element\Block\ArgumentInterface;
use Magento\Sales\Api\OrderRepositoryInterface;

class KlarnaServicePointProvider implements ArgumentInterface
{
    private RequestInterface $request;
    private OrderRepositoryInterface $orderRepository;
    private $order;

    /**
     * KlarnaServicePointProvider constructor.
     * @param RequestInterface $request
     * @param OrderRepositoryInterface $orderRepository
     */
    public function __construct(
        RequestInterface $request,
        OrderRepositoryInterface $orderRepository
    ) {
        $this->request = $request;
        $this->orderRepository = $orderRepository;
    }

    public function getMethod():string
    {
        $order = $this->getOrder();

        return $order->getKssMethod() ?? "";
    }

    public function getCarrier():string
    {
        $order = $this->getOrder();

        return $order->getKssCarrier() ?? "";
    }

    public function getClass():string
    {
        $order = $this->getOrder();

        return $order->getKssClass() ?? "";
    }

    public function getPickupLocationId():string
    {
        $order = $this->getOrder();

        return $order->getKssPickupLocationId() ?? "";
    }

    public function getTmsReference(): string
    {
        $order = $this->getOrder();

        return $order->getKssTmsReference() ?? "";
    }

    /**
     * @return \Magento\Sales\Api\Data\OrderInterface|bool
     */
    private function getOrder()
    {
        if (!$this->order) {
            $orderId = $this->request->getParam('order_id');
            if (!$orderId || is_int($orderId)) {
                return false;
            }
            $this->order = $this->orderRepository->get($orderId);
        }

        return $this->order;
    }
}
