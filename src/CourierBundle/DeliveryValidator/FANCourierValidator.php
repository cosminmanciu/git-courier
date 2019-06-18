<?php

namespace CourierBundle\DeliveryValidator;

use CommonBundle\Entity\Product\Product;
use CourierBundle\DeliveryValidator\Exception\DeliveryNotSupported;
use CourierBundle\DeliveryValidator\Exception\MaxProductLengthException;
use CourierBundle\DeliveryValidator\Exception\MaxWeightException;
use CourierBundle\DeliveryValidator\Exception\ProductTypeException;
use DeliveryBundle\Entity\Delivery;

class FANCourierValidator
{
    const MAX_WEIGHT = 40000;
    const MAX_PRODUCT_LENGTH = 2000;
    const MAX_DELIVERY_VOLUME = 1000000000;

    public function supportsDelivery(Delivery $delivery)
    {
        $maxLength = 0;
        $maxWidth = 0;
        $maxHeight = 0;

        foreach ($delivery->products as $product) {
            if ($product->type !== Product::DRY) {
                throw new ProductTypeException();
            }

            if ($product->weight > self::MAX_WEIGHT) {
                throw new MaxWeightException();
            }

            $dimmensions = [$product->length, $product->width, $product->height];
            rsort($dimmensions);
            list($length, $width, $height) = $dimmensions;
            if ($length > self::MAX_PRODUCT_LENGTH) {
                throw new DeliveryNotSupported();
            }

            $maxLength = max($maxLength, $length);
            $maxWidth = max($maxWidth, $width);
            $maxHeight += $height;
        }

        $totalVolume = $maxLength*$maxWidth*$maxHeight;
        if ($totalVolume > self::MAX_DELIVERY_VOLUME) {
            throw new MaxProductLengthException();
        }
    }
}
