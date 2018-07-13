<?php
/**
 * A helper file for your Eloquent Models
 * Copy the phpDocs from this file to the correct Model,
 * And remove them from this file, to prevent double declarations.
 *
 * @author Barry vd. Heuvel <barryvdh@gmail.com>
 */


namespace App{
/**
 * App\Email
 *
 * @property int $id
 * @property string $email
 * @property string $token
 * @property \Carbon\Carbon $created_at
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Email whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Email whereEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Email whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Email whereToken($value)
 */
	class Email extends \Eloquent {}
}

namespace App{
/**
 * App\Order
 *
 * @property int $id
 * @property string $order_id
 * @property float $amount
 * @property float|null $rate
 * @property float|null $gross
 * @property float|null $fee
 * @property float|null $net
 * @property float|null $tokens
 * @property float|null $bonus
 * @property string|null $invoice
 * @property string|null $comment
 * @property int|null $coingate_id
 * @property int $currency_id
 * @property int $status_id
 * @property int $user_id
 * @property string $hash
 * @property \Carbon\Carbon|null $created_at
 * @property \Carbon\Carbon|null $updated_at
 * @property-read \App\Status $status
 * @property-read \App\Currency $type
 * @property-read \App\User $user
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Order whereAmount($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Order whereBonus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Order whereCoingateId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Order whereComment($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Order whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Order whereCurrencyId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Order whereFee($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Order whereGross($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Order whereHash($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Order whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Order whereInvoice($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Order whereNet($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Order whereOrderId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Order whereRate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Order whereStatusId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Order whereTokens($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Order whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Order whereUserId($value)
 */
	class Order extends \Eloquent {}
}

namespace App{
/**
 * App\Response
 *
 * @property int $id
 * @property string $order_id
 * @property int $coingate_id
 * @property string $response
 * @property \Carbon\Carbon|null $created_at
 * @property \Carbon\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Response whereCoingateId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Response whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Response whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Response whereOrderId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Response whereResponse($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Response whereUpdatedAt($value)
 */
	class Response extends \Eloquent {}
}

namespace App{
/**
 * App\Session
 *
 * @property int $id
 * @property int|null $user_id
 * @property string|null $ip_address
 * @property string|null $user_agent
 * @property string $payload
 * @property \Carbon\Carbon $last_activity
 * @property-read \App\User $user
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Session whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Session whereIpAddress($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Session whereLastActivity($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Session wherePayload($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Session whereUserAgent($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Session whereUserId($value)
 */
	class Session extends \Eloquent {}
}

namespace App{
/**
 * App\User
 *
 * @property int $id
 * @property string $first_name
 * @property string $last_name
 * @property string $phone
 * @property string $email
 * @property string $password
 * @property int $verified
 * @property int $contributed
 * @property int $kyc
 * @property int $personal
 * @property string|null $company_name
 * @property int|null $company_code
 * @property string|null $company_vat
 * @property string|null $company_address
 * @property string|null $email_token
 * @property string|null $remember_token
 * @property string|null $verified_at
 * @property int $admin
 * @property \Carbon\Carbon|null $created_at
 * @property \Carbon\Carbon|null $updated_at
 * @property string|null $company_city
 * @property string|null $timezone
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Log[] $logs
 * @property-read \Illuminate\Notifications\DatabaseNotificationCollection|\Illuminate\Notifications\DatabaseNotification[] $notifications
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Order[] $orders
 * @method static \Illuminate\Database\Eloquent\Builder|\App\User whereAdmin($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\User whereCompanyAddress($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\User whereCompanyCity($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\User whereCompanyCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\User whereCompanyName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\User whereCompanyVat($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\User whereContributed($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\User whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\User whereEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\User whereEmailToken($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\User whereFirstName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\User whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\User whereKyc($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\User whereLastName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\User wherePassword($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\User wherePersonal($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\User wherePhone($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\User whereRememberToken($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\User whereTimezone($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\User whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\User whereVerified($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\User whereVerifiedAt($value)
 */
	class User extends \Eloquent {}
}

namespace App{
/**
 * App\Status
 *
 * @property int $id
 * @property string $title
 * @property string $description
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Status whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Status whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Status whereTitle($value)
 */
	class Status extends \Eloquent {}
}

namespace App{
/**
 * App\Currency
 *
 * @property int $id
 * @property string $title
 * @property string $short_title
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Currency whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Currency whereShortTitle($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Currency whereTitle($value)
 */
	class Currency extends \Eloquent {}
}

namespace App{
/**
 * App\Log
 *
 * @property int $id
 * @property int $user_id
 * @property string $ip_address
 * @property string $session_id
 * @property string $user_agent
 * @property \Carbon\Carbon $log_in
 * @property \Carbon\Carbon|null $log_out
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Log whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Log whereIpAddress($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Log whereLogIn($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Log whereLogOut($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Log whereSessionId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Log whereUserAgent($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Log whereUserId($value)
 */
	class Log extends \Eloquent {}
}

