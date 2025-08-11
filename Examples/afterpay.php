<?php
/*
 * The MIT License
 *
 * Copyright 2025 Jordi Jolink.
 *
 * Permission is hereby granted, free of charge, to any person obtaining a copy
 * of this software and associated documentation files (the "Software"), to deal
 * in the Software without restriction, including without limitation the rights
 * to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
 * copies of the Software, and to permit persons to whom the Software is
 * furnished to do so, subject to the following conditions:
 *
 * The above copyright notice and this permission notice shall be included in
 * all copies or substantial portions of the Software.
 *
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 * IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 * FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
 * AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
 * LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
 * OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN
 * THE SOFTWARE.
 */
require_once '_require.php';
require_once '_keys.php';

$authentication = new \Buckaroo\Authentication\Authentication($secretKey, $websiteKey);
$buckaroo = new \Buckaroo\Buckaroo($authentication, true);

$afterpayPayService = (new \Buckaroo\Services\Pay\Afterpay)
    ->setMerchantImageUrl('https://soneritics.nl/img/soneritics.png')
    ->setBillingCustomerCategory(\Buckaroo\Enums\CustomerCategory::PERSON)
    ->setBillingCustomerSalutation(\Buckaroo\Enums\CustomerSalutation::MR)
    ->setBillingCustomerFirstName("Jordi")
    ->setBillingCustomerLastName("Jolink")
    ->setBillingCustomerBirthDate(new \DateTime('15-06-1970 12:00:00'))
    ->setBillingCustomerStreet("Teststraat")
    ->setBillingCustomerStreetNumber(1)
    ->setBillingCustomerPostalCode('1234AA')
    ->setBillingCustomerCity('Amsterdam')
    ->setBillingCustomerCountry('NL')
    ->setBillingCustomerConversationLanguage('nl')
    ->setBillingCustomerMobilePhone('0612345678')
    ->setBillingCustomerEmail('jordi@soneritics.nl')
    ->setBillingCustomerCustomerNumber("C-TST-001")
    ->addArticle(
        'ABC-001',
        'Test product',
        2,
        20,
        21)

    ->shipmentAddressDiffers(true)
    ->setBillingCustomerCategory(\Buckaroo\Enums\CustomerCategory::PERSON)
    ->setBillingCustomerSalutation(\Buckaroo\Enums\CustomerSalutation::MR)
    ->setBillingCustomerFirstName("Jordi")
    ->setBillingCustomerLastName("Jolink")
    ->setBillingCustomerStreet("Hoofdstraat")
    ->setBillingCustomerStreetNumber(124)
    ->setBillingCustomerStreetNumberAdditional("a")
    ->setBillingCustomerPostalCode('5678XX')
    ->setBillingCustomerCity('Den Haag')
    ->setBillingCustomerCountry('NL')
    ->setBillingCustomerConversationLanguage('nl');

$afterpayTransactionRequest = $buckaroo->getTransactionRequest($afterpayPayService)
    ->setClientIp("192.168.1.1")
    ->setAmountDebit(50)
    ->setInvoice('ap-250811-001')
    ->request();

print_r($afterpayTransactionRequest);

echo "Your transaction has the key: {$afterpayTransactionRequest['Key']}" . PHP_EOL;

if (isset($afterpayTransactionRequest['Status']['Code']['Code'])
        && $afterpayTransactionRequest['Status']['Code']['Code'] == 190) {
    echo "Your transaction was successful!" . PHP_EOL;
} else {
    echo "Your transaction was NOT successful, for the following reason:" . PHP_EOL;
    echo $afterpayTransactionRequest['Status']['SubCode']['Description'] . PHP_EOL;
}
