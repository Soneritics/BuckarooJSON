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
namespace Buckaroo\Result;

use Buckaroo\Result\Models\Action;

/**
 * Class TransactionSpecificationResult
 * @package Buckaroo\Requests
 */
class TransactionSpecificationResult implements IResult
{
    /**
     * @var array
     */
    private $actions = [];

    /**
     * Parse from an array
     * @param array $data
     */
    public function __construct(array $data)
    {
        if (isset($data['Actions'])) {
            foreach ($data['Actions'] as $action) {
                if (!empty($action['Name'])) {
                    $this->actions[$action['Name']] = new Action($action);
                }
            }
        }
    }

    /**
     * Get the available actions
     * @return array
     */
    public function getActions(): array
    {
        return $this->actions;
    }
}
