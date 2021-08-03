# Enot.io payments

Package for working with the payment aggregator [API enot.io](https://enot.io/knowledge/index)

## Installation

Use the package manager [composer](https://getcomposer.org/) to install enot-io-payments.

```bash
composer require litlife/enot-io-payments
```

## Usage

### Generate a payment URL

In this example, you can see how to generate a link that the user can click to make a payment

```bash
use Litlife\EnotIoPayments\EnotIoApi;

$orderAmount = 42.2;
$orderId = 4242;
$currency = 'RUB';
$comment = 'Some text';

$url = (new EnotIoApi())
  ->setMerchantId(424242)
  ->setSecretKey('secret key')
  ->getPaymentUrl($orderAmount, $orderId, $currency, $comment);

print_r($url);
```

Output:

`https://enot.io/pay?m=424242&oa=42.20&o=4242&s=8a7d38fa09963aaadd32a8422fbfd397&cr=RUB&c=Some+text`

### Notifications after payment (webhook, callback)

```bash
use Litlife\EnotIoPayments\EnotIoApi;

$postArray = [
    'merchant' => 150,
    'amount' => 200.00,
    'credited' => 196.00,
    'intid' => 1545855,
    'merchant_id' => 99,
    'method' => 'cd',
    'sign' => 'cd1d6b67f3335038656d9009ab4ecfa9',
    'sign_2' => 'b86410d16a20bb57366d29b0d884bcb2',
    'currency' => 'RUB',
    'commission' => 0.00,
    'payer_details' => '539175******7523',
    'custom_field' => [
        'email' => 'test@email.ru',
        'id_user' => '125454'
    ]
];

$paymentStatus = (new EnotIoApi())
  ->setSecretKey2('secret key 2')
  // validate params with secret key 2
  ->paymentStatus($postArray);

print_r($request->getAmount());
print_r($request->getCredited());
print_r($request->getIntId());  
```

Output:

`
200.00
196.00
1545855
`

### Payment information

```bash
use Litlife\EnotIoPayments\EnotIoApi;

$shopId = 4242;
$enotTransactionId = 123;
$yourTransactionId = 456;

$paymentInfo = (new EnotIoApi())
  ->setEmail('test@test.com')
  ->setApiKey('api key')
  ->paymentInfo($shopId, $enotTransactionId, $yourTransactionId);
  
print_r($paymentInfo->getStatus());
print_r($paymentInfo->getCredited());
...
```

## Testing
```bash
composer test
```
## License
[MIT](https://choosealicense.com/licenses/mit/)
