<?php

namespace Src\Core\PaymentHandler;

class PaymentHandler
{
    public static function getPaymentSecret()
    {
        $config = require __DIR__ . '/../config/payment.php';
        return $config['payment_secret'];
    }
    public static function getPaymentSecretHash() {
        $config = require __DIR__ . '/../config/payment.php';
        return $config['secret_hash'];
    }
}
?>