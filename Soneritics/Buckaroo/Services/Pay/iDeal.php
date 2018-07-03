<?php
/*
 * The MIT License
 *
 * Copyright 2018 Jordi Jolink.
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

use Buckaroo\Exceptions\MissingParameterException;

/**
 * Class iDeal
 * @package Buckaroo\Services\Pay
 */
class iDeal extends AbstractPayService
{
    /**
     * Get this service's name
     * @return string
     */
    public function getName(): string
    {
        return 'ideal';
    }

    /**
     * Validate the parameters that have been filled
     * @param array $parameters
     * @throws MissingParameterException
     */
    public function validateParameters(array $parameters): void
    {
        $mandatory = ['issuer'];
        $parameters = $this->getParameters();

        foreach ($mandatory as $item) {
            if (!isset($parameters[$item]) || empty($parameters[$item])) {
                throw new MissingParameterException($item);
            }
        }
    }

    /**
     * Set the iDeal issuer
     * @param string $issuer
     * @return iDeal
     */
    public function setIssuer(string $issuer): iDeal
    {
        $this->set('issuer', $issuer);
        return $this;
    }
}
