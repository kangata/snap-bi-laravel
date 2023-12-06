# SNAP BI Laravel

Laravel package for use SNAP BI API


### Features
- [x] Get Access Token
- [x] Intrabank Transfer 
- [ ] ...

### Example Usage
```php
use QuetzalStudio\SnapBi\Amount;
use QuetzalStudio\SnapBi\Config;
use QuetzalStudio\SnapBi\IntrabankTransfer\Payload;
use QuetzalStudio\SnapBi\IntrabankTransfer\Transfer;

Config::load('bca');

$request = new Transfer(
    origin: config('app.url'),
    channelId: config('snap.providers.bca.channel_id'),
    externalId: 'YOUR EXTERNAL ID',
);

$response = $request->send(new Payload(
    partnerReferenceNo: 'TRX123',
    amount: new Amount(10000),
    beneficiaryAccountNo: '111111',
    sourceAccountNo: '999999',
    transactionDate: date(DATE_ATOM, time()),
    beneficiaryEmail: '',
    remark: '',
    currency: 'IDR',
    additionalInfo: [
        'economicActivity' => '',
        'transactionPurpose' => '',
    ],
));

// $response --> \Illuminate\Http\Client\Response
```