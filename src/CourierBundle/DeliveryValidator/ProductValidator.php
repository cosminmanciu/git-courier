<?php
/**
 * Created by PhpStorm.
 * User: ana.podeanu
 * Date: 6/18/2019
 * Time: 11:40 AM
 */

namespace CourierBundle\DeliveryValidator;


use CommonBundle\Entity\Product\Product;
use CourierBundle\DeliveryValidator\Exception\DeliveryNotSupported;

class ProductValidator
{

    const MAX_WEIGHT = 40000;
    const MAX_PRODUCT_LENGTH = 2000;
    const MAX_DELIVERY_VOLUME = 1000000000;


    /**
     * @param $product
     * @throws DeliveryNotSupported
     */
    public function hasValidType($product)
    {
        if ($product->type !== Product::DRY) {
            throw new DeliveryNotSupported();
        }
    }

    /**
     * @param $product
     * @throws DeliveryNotSupported
     */
    public function hasValidWeight($product)
    {
        if ($product->weight > self::MAX_WEIGHT) {
            throw new DeliveryNotSupported();
        }
    }

    /**
     * @param $product
     * @return array
     * @throws DeliveryNotSupported
     */
    public function hasValidDimensions($product)
    {
        $dimmensions = [$product->length, $product->width, $product->height];
        rsort($dimmensions);
        list($length, $width, $height) = $dimmensions;
        if ($length > self::MAX_PRODUCT_LENGTH) {
            throw new DeliveryNotSupported();
        }
        return array($length, $width, $height);
    }

    /**
     * @param $maxLength
     * @param $maxWidth
     * @param $maxHeight
     * @throws DeliveryNotSupported
     */
    public function hasValidVolume($maxLength = 0, $maxWidth = 0, $maxHeight = 0)
    {
        $totalVolume = $maxLength * $maxWidth * $maxHeight;
        if ($totalVolume > self::MAX_DELIVERY_VOLUME) {
            throw new DeliveryNotSupported();
        }
    }
}