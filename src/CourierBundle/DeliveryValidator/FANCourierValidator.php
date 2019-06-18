<?php

namespace CourierBundle\DeliveryValidator;

use CommonBundle\Entity\Product\Product;
use CourierBundle\DeliveryValidator\Exception\DeliveryNotSupported;
use DeliveryBundle\Entity\Delivery;

/**
 * Class FANCourierValidator
 * @package CourierBundle\DeliveryValidator
 */
class FANCourierValidator
{
    const MAX_WEIGHT = 40000;
    const MAX_PRODUCT_LENGTH = 2000;
    const MAX_DELIVERY_VOLUME = 1000000000;

    /**
     * @param Delivery $delivery
     * @throws DeliveryNotSupported
     */
    public function supportsDelivery(Delivery $delivery)
    {
        $maxLength = 0;
        $maxWidth = 0;
        $maxHeight = 0;

        foreach ($delivery->getProducts() as $product) {
            if ($product->getType() !== Product::DRY) {
                throw new DeliveryNotSupported();
            }

            if ($product->getWeight() > self::MAX_WEIGHT) {
                throw new DeliveryNotSupported();
            }

            $dimensions = [$product->getLength(), $product->getWeight(), $product->getHeight()];
            rsort($dimensions);
            list($length, $width, $height) = $dimensions;
            if ($length > self::MAX_PRODUCT_LENGTH) {
                throw new DeliveryNotSupported();
            }

            $maxLength = max($maxLength, $length);
            $maxWidth = max($maxWidth, $width);
            $maxHeight += $height;
        }

        $totalVolume = $maxLength*$maxWidth*$maxHeight;
        if ($totalVolume > self::MAX_DELIVERY_VOLUME) {
            throw new DeliveryNotSupported();
        }
    }
}
