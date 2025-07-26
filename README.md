# SMS4Jawaly for Laravel

مكتبة **SMS4Jawaly** الخاصة بإطار العمل **Laravel** تسمح لك بإرسال الرسائل النصية القصيرة واستعراض أسماء المرسلين المتاحة والاطلاع على رصيد حسابك في خدمة فورجوالي.

This package provides an easy integration with the 4Jawaly SMS gateway for Laravel applications. It exposes a simple API for sending SMS messages, retrieving sender names and checking your account balance.

## التثبيت | Installation

قم بتثبيت الحزمة باستخدام Composer:

```bash
composer require sms4jawaly/laravel-sdk
```

## الإعداد | Configuration

أضف بيانات الاعتماد الخاصة بك في ملف `.env` ثم قم بتحديث ملف `config/services.php`:

Add your API credentials to your `.env` file and configure the `services.php` file.

```env
SMS4JAWALY_API_KEY=your_api_key
SMS4JAWALY_API_SECRET=your_api_secret
```

ثم في ملف `config/services.php` أضف ما يلي تحت المصفوفة `services`:

Add the following under the `services` array in `config/services.php`:

```php
'sms4jawaly' => [
    'api_key'    => env('SMS4JAWALY_API_KEY'),
    'api_secret' => env('SMS4JAWALY_API_SECRET'),
],
```

إذا كنت تستخدم **التخزين المؤقت للتكوين** (`config:cache`)، تأكد من إعادة تشغيله بعد إضافة الحقول الجديدة.

## الاستخدام | Usage

### إرسال رسالة SMS | Sending an SMS

يمكنك استخدام الواجهة `Gateway` مباشرة عبر الاستدعاء من الحاوية:

You can resolve the `Gateway` class from the container:

```php
use Sms4jawaly\Laravel\Gateway;

$sms = app(Gateway::class);

$response = $sms->sendSms(
    'رسالة تجريبية من فورجوالي', // نص الرسالة | Message text
    ['966500000000'],            // أرقام المستلمين | Recipient numbers
    '4jawaly'                    // اسم المرسل | Sender name
);
```

أو عبر الـ **Facade**:

Or via the Facade:

```php
use Sms4jawaly\Laravel\Facades\Sms4jawaly;

$response = Sms4jawaly::sendSms('Test message', ['966500000000'], '4jawaly');
```

### جلب الرصيد | Get Balance

```php
$balance = $sms->getBalance();
// أو
$balance = Sms4jawaly::getBalance();
```

### جلب أسماء المرسلين | Get Sender Names

```php
$senders = $sms->getSenders();
// أو
$senders = Sms4jawaly::getSenders();
```

## المساهمة | Contributing

نرحب بمساهماتكم! يرجى إرسال pull request على المستودع الرسمي.

Contributions are welcome! Please submit a pull request to the official repository.

## الترخيص | License

هذه المكتبة مرخصة تحت رخصة MIT.

This package is licensed under the MIT License.