<?php

declare(strict_types = 1);

namespace QuetzalStudio\SnapBi;

class HeaderFactory
{
    public static function make(array $attributes)
    {
        return new Header(
            contentType: data_get($attributes, 'content_type', 'application/json'),
            clientKey: data_get($attributes, 'client_key'),
            authorization: data_get($attributes, 'authorization'),
            authorizationCustomer: data_get($attributes, 'authorization_customer'),
            timestamp: (string) new Timestamp(data_get($attributes, 'timestamp')),
            signature: data_get($attributes, 'signature'),
            origin: data_get($attributes, 'origin'),
            partnerId: data_get($attributes, 'partner_id'),
            externalId: data_get($attributes, 'external_id'),
            ipAddress: data_get($attributes, 'ip_address'),
            deviceId: data_get($attributes, 'device_id'),
            latitude: data_get($attributes, 'latitude'),
            longitude: data_get($attributes, 'longitude'),
            channelId: data_get($attributes, 'channel_id'),
        );
    }
}
