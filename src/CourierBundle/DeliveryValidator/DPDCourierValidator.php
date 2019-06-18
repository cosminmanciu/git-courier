<?php

namespace CourierBundle\DeliveryValidator;

use CourierBundle\DeliveryValidator\Exception\DeliveryNotSupported;
use DeliveryBundle\Entity\Delivery;

class DPDCourierValidator
{
    /**
     * @param Delivery $delivery
     * @param ProductValidator $productValidator
     * @throws DeliveryNotSupported
     */
    public function supportsDelivery(Delivery $delivery, ProductValidator $productValidator)
    {
        $maxLength = 0;
        $maxWidth = 0;
        $maxHeight = 0;

        foreach ($delivery->getProducts() as $product) {
            $productValidator->hasValidType($product);
            $productValidator->hasValidWeight($product);
            $productValidator->hasValidDimensions($product);

            $maxLength = max($maxLength, $product->getLength());
            $maxWidth = max($maxWidth, $product->getWidth());
            $maxHeight += $product->getHeight();
        }

        $productValidator->hasValidVolume($maxLength, $maxWidth, $maxHeight);
    }
}
