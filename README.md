# 4Jawaly Laravel SDK

[![License: MIT](https://img.shields.io/badge/License-MIT-yellow.svg)](https://opensource.org/licenses/MIT)

A comprehensive Laravel SDK for the [4Jawaly](https://www.4jawaly.com/) SMS gateway service. This package provides an elegant and simple API for sending SMS messages, checking account balance, retrieving sender names, and seamless integration with Laravel's Notification system.

مكتبة شاملة لإطار العمل Laravel للتكامل مع خدمة بوابة الرسائل القصيرة [فورجوالي](https://www.4jawaly.com/). توفر هذه الحزمة واجهة برمجية بسيطة وأنيقة لإرسال الرسائل النصية القصيرة، والاطلاع على رصيد الحساب، واستعراض أسماء المرسلين، مع دعم كامل لنظام الإشعارات في Laravel.

## Table of Contents | جدول المحتويات

- [Features](#features)
- [Requirements](#requirements)
- [Installation](#installation)
- [Configuration](#configuration)
- [Usage](#usage)
  - [Sending SMS](#sending-sms)
  - [Getting Account Balance](#getting-account-balance)
  - [Getting Sender Names](#getting-sender-names)
  - [Using Laravel Notifications](#using-laravel-notifications)
- [API Reference](#api-reference)
- [Error Handling](#error-handling)
- [Contributing](#contributing)
- [License](#license)

## Features | المميزات

- ✅ Send SMS messages to single or multiple recipients | إرسال الرسائل القصيرة لمستلم واحد أو متعددين
- ✅ Check account balance and active packages | الاطلاع على رصيد الحساب والباقات النشطة
- ✅ Retrieve available sender names | استعراض أسماء المرسلين المتاحة
- ✅ Full Laravel Notification Channel support | دعم كامل لقنوات الإشعارات في Laravel
- ✅ On-demand notifications without user model | إرسال الإشعارات الفورية دون نموذج مستخدم
- ✅ Automatic service provider registration | تسجيل تلقائي لمزود الخدمة
- ✅ Simple Facade for easy access | واجهة Facade بسيطة للوصول السريع
- ✅ Comprehensive error handling | معالجة شاملة للأخطاء

## Requirements | المتطلبات

- PHP >= 7.2
- Laravel Framework (any version)
- Guzzle HTTP Client ^7.0

## Installation | التثبيت

Install the package via Composer:

قم بتثبيت الحزمة باستخدام Composer:

```bash
composer require abather/sms4jawaly
```

The service provider will be automatically registered thanks to Laravel's package auto-discovery.

سيتم تسجيل مزود الخدمة تلقائياً بفضل خاصية الاكتشاف التلقائي في Laravel.

## Configuration | الإعداد

### 1. Publish Configuration | نشر ملف الإعدادات

Publish the package configuration file to your application:

انشر ملف إعدادات الحزمة إلى تطبيقك:

```bash
php artisan vendor:publish --provider="Sms4jawaly\Laravel\Sms4jawalyServiceProvider"
```

This will create a `config/sms-4-jawaly.php` file in your application.

سيؤدي هذا إلى إنشاء ملف `config/sms-4-jawaly.php` في تطبيقك.

### 2. Environment Variables | متغيرات البيئة

Add your 4Jawaly API credentials to your `.env` file:

أضف بيانات الاعتماد الخاصة بـ 4Jawaly في ملف `.env`:

```env
SMS4JAWALY_API_KEY=your_api_key_here
SMS4JAWALY_API_SECRET=your_api_secret_here
SMS4JAWALY_DEFAULT_SENDER=4jawaly
SMS4JAWALY_RECEIVER_ATTRIBUTE=phone
```

### 3. Configuration File | ملف الإعدادات

The published configuration file `config/sms-4-jawaly.php` contains:

يحتوي ملف الإعدادات المنشور `config/sms-4-jawaly.php` على:

```php
return [
    'api_key'            => env('SMS4JAWALY_API_KEY'),
    'api_secret'         => env('SMS4JAWALY_API_SECRET'),
    'default_sender'     => env('SMS4JAWALY_DEFAULT_SENDER', '4jawaly'),
    'receiver_attribute' => env('SMS4JAWALY_RECEIVER_ATTRIBUTE', 'phone'),
];
```

> **Note:** If you're using configuration caching (`php artisan config:cache`), remember to clear and recache after making changes.
>
> **ملاحظة:** إذا كنت تستخدم التخزين المؤقت للإعدادات (`php artisan config:cache`)، تذكر مسح وإعادة التخزين المؤقت بعد إجراء التغييرات.

## Usage | الاستخدام

### Sending SMS | إرسال الرسائل القصيرة

#### Using the Gateway Class | استخدام كلاس Gateway

You can resolve the `Gateway` class directly from Laravel's service container:

يمكنك استدعاء كلاس `Gateway` مباشرة من حاوية خدمات Laravel:

```php
use Sms4jawaly\Laravel\Gateway;

$gateway = app(Gateway::class);

$response = $gateway->sendSms(
    'مرحباً بك في خدمتنا!',  // Message text | نص الرسالة
    ['966500000000'],          // Recipient numbers | أرقام المستلمين
    '4jawaly'                  // Sender name | اسم المرسل
);

// Response structure:
// [
//     'success'       => true,
//     'total_success' => 1,
//     'total_failed'  => 0,
//     'job_ids'       => ['job_id_here'],
//     'errors'        => []
// ]
```

#### Using the Facade | استخدام الـ Facade

Alternatively, use the convenient Facade:

أو استخدم الـ Facade البسيط:

```php
use Sms4jawaly\Laravel\Facades\Sms4jawaly;

$response = Sms4jawaly::sendSms(
    'Your verification code is: 123456',
    ['966500000000', '966511111111'],  // Send to multiple numbers
    '4jawaly'
);
```

### Getting Account Balance | الاطلاع على الرصيد

Retrieve your current account balance and active packages:

استعرض رصيد حسابك والباقات النشطة:

```php
use Sms4jawaly\Laravel\Facades\Sms4jawaly;

$balance = Sms4jawaly::getBalance();

// Response structure:
// [
//     'success' => true,
//     'data'    => [...] // API response data
// ]

if ($balance['success']) {
    // Process balance data
    $packages = $balance['data'];
}
```

### Getting Sender Names | الحصول على أسماء المرسلين

Retrieve all available sender names for your account:

استعرض جميع أسماء المرسلين المتاحة لحسابك:

```php
use Sms4jawaly\Laravel\Facades\Sms4jawaly;

$senders = Sms4jawaly::getSenders();

// Response structure:
// [
//     'success'         => true,
//     'all_senders'     => ['4jawaly', 'MySender'],
//     'default_senders' => ['4jawaly'],
//     'message'         => 'تم'
// ]

if ($senders['success']) {
    $allSenders = $senders['all_senders'];
    $defaultSenders = $senders['default_senders'];
}
```

### Using Laravel Notifications | استخدام نظام الإشعارات

The package provides a notification channel for seamless integration with Laravel's notification system.

توفر الحزمة قناة إشعارات للتكامل السلس مع نظام الإشعارات في Laravel.

#### Creating a Notification | إنشاء إشعار

Create a notification class using Laravel's artisan command:

أنشئ كلاس الإشعار باستخدام أمر artisan:

```bash
php artisan make:notification WelcomeNotification
```

Then implement the notification:

ثم قم بتطبيق الإشعار:

```php
<?php

namespace App\Notifications;

use Illuminate\Notifications\Notification;
use Sms4jawaly\Laravel\Message;
use Sms4jawaly\Laravel\Sms4JawalyChannel;

class WelcomeNotification extends Notification
{
    public function via($notifiable)
    {
        return [Sms4JawalyChannel::class];
    }

    public function to4Jawaly($notifiable): Message
    {
        return Message::make("Welcome {$notifiable->name}!")
            ->phone($notifiable->phone)    // Optional: override phone number
            ->sender('4jawaly');           // Optional: override sender name
    }
}
```

#### Message Class Methods | طرق كلاس Message

The `Message` class provides a fluent interface for building SMS messages:

يوفر كلاس `Message` واجهة سلسة لبناء الرسائل:

```php
Message::make('Your message text')
    ->phone('966500000000')  // Set recipient phone number
    ->sender('4jawaly')      // Set sender name
    ->message('New text');   // Update message text
```

#### Simple String Response | الاستجابة بنص بسيط

For simple messages, you can return a string directly:

للرسائل البسيطة، يمكنك إرجاع نص مباشرة:

```php
public function to4Jawaly($notifiable): string
{
    return "Your verification code is: {$this->code}";
}
```

#### Sending Notifications | إرسال الإشعارات

Send the notification to a user model:

أرسل الإشعار إلى نموذج المستخدم:

```php
use App\Notifications\WelcomeNotification;

$user = User::find(1);
$user->notify(new WelcomeNotification());
```

#### Routing Notifications | توجيه الإشعارات

The channel automatically routes notifications using:

تقوم القناة تلقائياً بتوجيه الإشعارات باستخدام:

1. `routeNotificationFor('sms-4-jawaly')` method on the notifiable
2. `routeNotificationFor(Sms4JawalyChannel::class)` method on the notifiable
3. The configured `receiver_attribute` (default: `phone`) property on the notifiable

You can customize the routing in your User model:

يمكنك تخصيص التوجيه في نموذج المستخدم:

```php
public function routeNotificationFor($channel)
{
    if ($channel === 'sms-4-jawaly') {
        return $this->mobile_number; // Use a different attribute
    }

    return $this->phone;
}
```

#### On-Demand Notifications | الإشعارات الفورية

Send notifications without associating them with a specific user model:

أرسل الإشعارات دون ربطها بنموذج مستخدم محدد:

```php
use App\Notifications\WelcomeNotification;
use Illuminate\Support\Facades\Notification;

Notification::route('sms-4-jawaly', '966500000000')
    ->notify(new WelcomeNotification());
```

Send to multiple numbers:

الإرسال لعدة أرقام:

```php
Notification::route('sms-4-jawaly', ['966500000000', '966511111111'])
    ->notify(new WelcomeNotification());
```

## API Reference | مرجع الـ API

### Gateway Class

#### `sendSms(string $message, array $numbers, string $sender): array`

Sends an SMS message to one or more recipients.

**Parameters:**
- `$message` (string): The message text to send
- `$numbers` (array): Array of recipient phone numbers in international format
- `$sender` (string): The sender name/ID

**Returns:**
```php
[
    'success'       => bool,    // Overall success status
    'total_success' => int,     // Number of successful sends
    'total_failed'  => int,     // Number of failed sends
    'job_ids'       => array,   // Array of job IDs from the API
    'errors'        => array    // Errors by error message => numbers
]
```

#### `getBalance(): array`

Retrieves the current account balance and active packages.

**Returns:**
```php
[
    'success' => bool,   // Success status
    'data'    => mixed,  // API response data
    'error'   => string  // Error message (if failed)
]
```

#### `getSenders(): array`

Retrieves all available sender names for the account.

**Returns:**
```php
[
    'success'         => bool,     // Success status
    'all_senders'     => array,    // All available sender names
    'default_senders' => array,    // Default sender names
    'message'         => string,   // Success message
    'error'           => string    // Error message (if failed)
]
```

### Message Class

#### `make(string $message): Message`

Static factory method to create a new Message instance.

#### `message(string $message): self`

Set or update the message text.

#### `phone(string|array $phone): self`

Set the recipient phone number(s).

#### `sender(string $sender): self`

Set the sender name.

#### `getMessage(): string`

Get the message text.

#### `getPhone(): string|array`

Get the recipient phone number(s).

#### `getSender(): string`

Get the sender name (returns default sender if not set).

## Error Handling | معالجة الأخطاء

All Gateway methods return arrays with a `success` key. Always check this before processing the response:

جميع طرق Gateway ترجع مصفوفات تحتوي على مفتاح `success`. تحقق دائماً من هذا المفتاح قبل معالجة الاستجابة:

```php
$response = Sms4jawaly::sendSms('Hello', ['966500000000'], '4jawaly');

if ($response['success']) {
    // Message sent successfully
    $jobIds = $response['job_ids'];
} else {
    // Handle errors
    foreach ($response['errors'] as $error => $numbers) {
        Log::error("Failed to send to " . implode(',', $numbers) . ": $error");
    }
}
```

For balance and sender retrieval:

للرصيد وأسماء المرسلين:

```php
$balance = Sms4jawaly::getBalance();

if (!$balance['success']) {
    Log::error('Failed to get balance: ' . $balance['error']);
}
```

## Contributing | المساهمة

Contributions are welcome! Please feel free to submit a Pull Request. For major changes, please open an issue first to discuss what you would like to change.

نرحب بمساهماتكم! لا تتردد في إرسال Pull Request. للتغييرات الكبيرة، يرجى فتح Issue أولاً لمناقشة ما تريد تغييره.

### Development Setup

```bash
# Clone the repository
git clone https://github.com/Abather/sms4jawaly.git

# Install dependencies
composer install

# Run tests (if available)
composer test
```

## License | الترخيص

This package is licensed under the MIT License. See the LICENSE file for more information.

هذه الحزمة مرخصة تحت رخصة MIT. راجع ملف LICENSE لمزيد من المعلومات.

## About | حول

This package is a fork of the official 4Jawaly SDK, enhanced with additional features and improvements for better Laravel integration.

هذه الحزمة مشتقة من الحزمة الرسمية لـ 4Jawaly، مع تحسينات وميزات إضافية لتكامل أفضل مع Laravel.

---

Made with ❤️ for the Laravel community | صُنع بـ ❤️ لمجتمع Laravel
