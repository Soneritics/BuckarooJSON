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
namespace Buckaroo\Services\Pay;

use Buckaroo\Enums\CustomerCategory;
use Buckaroo\Enums\CustomerCountry;
use Buckaroo\Enums\CustomerLanguage;
use Buckaroo\Enums\CustomerSalutation;
use Buckaroo\Enums\Gender;
use Buckaroo\Exceptions\MissingParameterException;
use Buckaroo\Exceptions\NoArticlesProvidedException;

/**
 * Class Afterpay
 * @package Buckaroo\Services\Pay
 */
class Afterpay extends AbstractPayService
{
    /**
     * @var array
     */
    private $articles = [];

    /**
     * @var array
     */
    private $serviceParameters = [];

    /**
     * @var bool
     */
    private $addressesDiffer = false;

    /**
     * Get this service's name
     * @return string
     */
    public function getName(): string
    {
        return 'Afterpay';
    }

    /**
     * Validate the parameters that have been filled
     * @param array $parameters
     * @param array $mandatory
     * @throws MissingParameterException
     * @throws NoArticlesProvidedException
     */
    public function validateParameters(array $parameters, array $mandatory = []): void
    {
        $mandatoryServiceParameters = [
            'BillingCustomerCategory',
            'BillingCustomerFirstName',
            'BillingCustomerLastName',
            'BillingCustomerSalutation',
            'BillingCustomerFirstName',
            'BillingCustomerLastName',
            'BillingCustomerBirthDate',
            'BillingCustomerStreet',
            'BillingCustomerStreetNumber',
            'BillingCustomerPostalCode',
            'BillingCustomerCity',
            'BillingCustomerCountry',
            'BillingCustomerMobilePhone',
            'BillingCustomerEmail',
            'BillingCustomerConversationLanguage'
        ];

        if ($this->addressesDiffer) {
            $mandatoryServiceParameters += [
                'ShippingCustomerCategory',
                'ShippingCustomerFirstName',
                'ShippingCustomerLastName',
                'ShippingCustomerStreet',
                'ShippingCustomerStreetNumber',
                'ShippingCustomerPostalCode',
                'ShippingCustomerCity',
                'ShippingCustomerConversationLanguage'
            ];
        }

        if (empty($this->articles)) {
            throw new NoArticlesProvidedException('No articles have been provided');
        }

        foreach ($mandatoryServiceParameters as $item) {
            if (!isset($this->serviceParameters[$item]) || empty($this->serviceParameters[$item])) {
                throw new MissingParameterException($item);
            }
        }

        parent::validateParameters($parameters, $mandatory);
    }

    /**
     * Give the possibility to add extra information to the parameter list
     * @param array $parameters
     * @return array
     */
    public function complementParameterList(array $parameters): array
    {
        foreach ($this->articles as $articleCount => $articleData) {
            foreach ($articleData as $name => $value) {
                $parameters[] = [
                    'Name' => $name,
                    'GroupType' => 'Article',
                    'GroupID' => $articleCount,
                    'Value' => $value
                ];
            }
        }

        foreach ($this->serviceParameters as $serviceParameter) {
            $parameters[] = [
                'Name' => $serviceParameter['Name'],
                'GroupType' => $serviceParameter['GroupType'],
                'Value' => $serviceParameter['Value'],
                "GroupID" => ""
            ];
        }

        return $parameters;
    }

    /**
     * Add an article.
     * At least one article is required.
     * @param string $id
     * @param string $description
     * @param int $quantity
     * @param float $unitPrice
     * @param float $vatPercentage
     * @return Afterpay
     */
    public function addArticle(
        string $id,
        string $description,
        int $quantity,
        float $unitPrice,
        float $vatPercentage
    ): Afterpay {
        $this->articles[] = [
            'Identifier' => $id,
            'Description' => $description,
            'Quantity' => $quantity,
            'GrossUnitPrice' => $unitPrice,
            'VatPercentage' => $vatPercentage
        ];

        return $this;
    }

    /**
     * Specify whether the shipment address differs from the billing address.
     * @param bool $value
     * @return Afterpay
     */
    public function shipmentAddressDiffers(bool $value): Afterpay
    {
        $this->addressesDiffer = $value;
        return $this;
    }

    /**
     * Image URL for the merchants brand. This image is shown at the top of the customer's order page in My Riverty.
     * @param string $value
     * @return $this
     */
    public function setMerchantImageUrl(string $value): Afterpay
    {
        $this->setServiceParameter(
            'MerchantImageUrl',
            $value,
            '');

        return $this;
    }

    /**
     * Customer category of billing customer.
     * @param string $value
     * @return $this
     */
    public function setBillingCustomerCategory(string $value = CustomerCategory::PERSON): Afterpay
    {
        $this->setServiceParameter(
            'Category',
            $value,
            'BillingCustomer');

        return $this;
    }

    /**
     * Company name. Mandatory for B2B.
     * @param string $value
     * @return $this
     */
    public function setBillingCustomerCompanyName(string $value): Afterpay
    {
        $this->setServiceParameter(
            'CompanyName',
            $value,
            'BillingCustomer');

        return $this;
    }

    /**
     * Required if Billing country is NL or BE. Gender of billing customer.
     * @param string $value
     * @return $this
     */
    public function setBillingCustomerSalutation(string $value = CustomerSalutation::MR): Afterpay
    {
        $this->setServiceParameter(
            'Salutation',
            $value,
            'BillingCustomer');

        return $this;
    }

    /**
     * First name of billing customer.
     * @param string $value
     * @return $this
     */
    public function setBillingCustomerFirstName(string $value): Afterpay
    {
        $this->setServiceParameter(
            'FirstName',
            $value,
            'BillingCustomer');

        return $this;
    }

    /**
     * Last name of billing customer, prefix included.
     * @param string $value
     * @return $this
     */
    public function setBillingCustomerLastName(string $value): Afterpay
    {
        $this->setServiceParameter(
            'LastName',
            $value,
            'BillingCustomer');

        return $this;
    }

    /**
     * Birth date of billing customer.
     * @param \DateTime $value
     * @return $this
     */
    public function setBillingCustomerBirthDate(\DateTime $value): Afterpay
    {
        $this->setServiceParameter(
            'BirthDate',
            $value->format('d-m-Y'),
            'BillingCustomer');

        return $this;
    }

    /**
     * Street of billing customer.
     * @param string $value
     * @return $this
     */
    public function setBillingCustomerStreet(string $value): Afterpay
    {
        $this->setServiceParameter(
            'Street',
            $value,
            'BillingCustomer');

        return $this;
    }

    /**
     * House number of billing customer.
     * @param int $value
     * @return $this
     */
    public function setBillingCustomerStreetNumber(int $value): Afterpay
    {
        $this->setServiceParameter(
            'StreetNumber',
            $value,
            'BillingCustomer');

        return $this;
    }

    /**
     * House number suffix of billing customer.
     * @param string $value
     * @return $this
     */
    public function setStreetNumberAdditional(string $value): Afterpay
    {
        $this->setServiceParameter(
            'StreetNumberAdditional',
            $value,
            'BillingCustomer');

        return $this;
    }

    /**
     * Postal code of billing customer.
     * @param string $value
     * @return $this
     */
    public function setBillingCustomerPostalCode(string $value): Afterpay
    {
        $this->setServiceParameter(
            'PostalCode',
            $value,
            'BillingCustomer');

        return $this;
    }

    /**
     * City of billing customer.
     * @param string $value
     * @return $this
     */
    public function setBillingCustomerCity(string $value): Afterpay
    {
        $this->setServiceParameter(
            'City',
            $value,
            'BillingCustomer');

        return $this;
    }

    /**
     * Country of billing customer. Possible values: NL, BE, DE, AT, FI.
     * @param string $value
     * @return $this
     */
    public function setBillingCustomerCountry(string $value = CustomerCountry::NL): Afterpay
    {
        $this->setServiceParameter(
            'Country',
            $value,
            'BillingCustomer');

        return $this;
    }

    /**
     * Mobile phone number of the billing customer.
     * @param string $value
     * @return $this
     */
    public function setBillingCustomerMobilePhone(string $value): Afterpay
    {
        $this->setServiceParameter(
            'MobilePhone',
            $value,
            'BillingCustomer');

        return $this;
    }

    /**
     * Email address of billing customer.
     * @param string $value
     * @return $this
     */
    public function setBillingCustomerEmail(string $value): Afterpay
    {
        $this->setServiceParameter(
            'Email',
            $value,
            'BillingCustomer');

        return $this;
    }

    /**
     * Conversation language of billing customer. Possible values: NL, FR, DE, FI.
     * @param string $value
     * @return $this
     */
    public function setBillingCustomerConversationLanguage(string $value = CustomerLanguage::NL): Afterpay
    {
        $this->setServiceParameter(
            'ConversationLanguage',
            $value,
            'BillingCustomer');

        return $this;
    }

    /**
     * The number you assign to the billing customer.
     * @param string $value
     * @return $this
     */
    public function setBillingCustomerCustomerNumber(string $value): Afterpay
    {
        $this->setServiceParameter(
            'CustomerNumber',
            $value,
            'BillingCustomer');

        return $this;
    }

    /*
    public function set(string $value): Afterpay
    {
        $this->setServiceParameter(
            '',
            $value,
            '');

        return $this;
    }
    */

    private function setServiceParameter(string $name, string $value, string $groupType = ''): Afterpay
    {
        $this->serviceParameters["{$groupType}{$name}"] = [
            'Name' => $name,
            'Value' => $value,
            'GroupType' => $groupType,
        ];

        return $this;
    }
}
