<?php

namespace App\Traits;

use App\Exceptions\InvalidMessageException;
use Illuminate\Support\Facades\Validator;
use PhpAmqpLib\Message\AMQPMessage;

trait AMQPMessageValidator
{
    /**
     * Validate the structure of an order message.
     *
     * @param AMQPMessage $message
     * @return bool
     * @throws InvalidMessageException
     */
    public function validateOrderMessage(AMQPMessage $message): bool
    {
        $data = json_decode($message->getBody(), true);

        $validator = Validator::make($data, [
            'order' => 'required|array',
            'order.id' => 'required|string|exists:orders,id',
        ]);

        if ($validator->fails()) {
            throw new InvalidMessageException(
                'Invalid order message structure',
                $validator->errors()->toArray(),
                $data
            );
        }

        return true;
    }
}