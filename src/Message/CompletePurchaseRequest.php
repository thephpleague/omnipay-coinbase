<?php

namespace Omnipay\Coinbase\Message;

use Omnipay\Common\Exception\InvalidRequestException;

/**
 * Coinbase Complete Purchase Request
 *
 * @method \Omnipay\Coinbase\Message\Response send()
 */
class CompletePurchaseRequest extends FetchTransactionRequest
{
    public function getData()
    {
        // check both GET and POST
        $order = $this->httpRequest->request->get('order') ?:
            $this->httpRequest->query->get('order');

        if (empty($order['id'])) {
            throw new InvalidRequestException('Missing Order ID');
        }

        return array('id' => $order['id']);
    }
}
