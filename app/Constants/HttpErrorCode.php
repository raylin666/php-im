<?php

declare(strict_types=1);
/**
 * This file is part of Hyperf.
 *
 * @link     https://www.hyperf.io
 * @document https://hyperf.wiki
 * @contact  group@hyperf.io
 * @license  https://github.com/hyperf/hyperf/blob/master/LICENSE
 */
namespace App\Constants;

use Hyperf\Constants\AbstractConstants;
use Hyperf\Constants\Annotation\Constants;

/**
 * @method $this getMessage($code)
 * @Constants
 */
class HttpErrorCode extends AbstractConstants
{
    /**
     * @Message("Continue")
     */
    const HTTP_CONTINUE = 100;

    /**
     * @Message("Switching Protocols")
     */
    const HTTP_SWITCHING_PROTOCOLS = 101;

    /**
     * @Message("Processing")
     */
    const HTTP_PROCESSING = 102;            // RFC2518

    /**
     * @Message("OK")
     */
    const HTTP_OK = 200;

    /**
     * @Message("Created")
     */
    const HTTP_CREATED = 201;

    /**
     * @Message("Accepted")
     */
    const HTTP_ACCEPTED = 202;

    /**
     * @Message("Non-Authoritative Information")
     */
    const HTTP_NON_AUTHORITATIVE_INFORMATION = 203;

    /**
     * @Message("No Content")
     */
    const HTTP_NO_CONTENT = 204;

    /**
     * @Message("Reset Content")
     */
    const HTTP_RESET_CONTENT = 205;

    /**
     * @Message("Partial Content")
     */
    const HTTP_PARTIAL_CONTENT = 206;

    /**
     * @Message("Multi-Status")
     */
    const HTTP_MULTI_STATUS = 207;          // RFC4918

    /**
     * @Message("Already Reported")
     */
    const HTTP_ALREADY_REPORTED = 208;      // RFC5842

    /**
     * @Message("IM Used")
     */
    const HTTP_IM_USED = 226;               // RFC3229

    /**
     * @Message("Multiple Choices")
     */
    const HTTP_MULTIPLE_CHOICES = 300;

    /**
     * @Message("Moved Permanently")
     */
    const HTTP_MOVED_PERMANENTLY = 301;

    /**
     * @Message("Found")
     */
    const HTTP_FOUND = 302;

    /**
     * @Message("See Other")
     */
    const HTTP_SEE_OTHER = 303;

    /**
     * @Message("Not Modified")
     */
    const HTTP_NOT_MODIFIED = 304;

    /**
     * @Message("Use Proxy")
     */
    const HTTP_USE_PROXY = 305;

    /**
     * @Message("Reserved")
     */
    const HTTP_RESERVED = 306;

    /**
     * @Message("Temporary Redirect")
     */
    const HTTP_TEMPORARY_REDIRECT = 307;

    /**
     * @Message("Permanent Redirect")
     */
    const HTTP_PERMANENTLY_REDIRECT = 308;  // RFC7238

    /**
     * @Message("Bad Request")
     */
    const HTTP_BAD_REQUEST = 400;

    /**
     * @Message("Unauthorized")
     */
    const HTTP_UNAUTHORIZED = 401;

    /**
     * @Message("Payment Required")
     */
    const HTTP_PAYMENT_REQUIRED = 402;

    /**
     * @Message("Forbidden")
     */
    const HTTP_FORBIDDEN = 403;

    /**
     * @Message("Not Found")
     */
    const HTTP_NOT_FOUND = 404;

    /**
     * @Message("Method Not Allowed")
     */
    const HTTP_METHOD_NOT_ALLOWED = 405;

    /**
     * @Message("Not Acceptable")
     */
    const HTTP_NOT_ACCEPTABLE = 406;

    /**
     * @Message("Proxy Authentication Required")
     */
    const HTTP_PROXY_AUTHENTICATION_REQUIRED = 407;

    /**
     * @Message("Request Timeout")
     */
    const HTTP_REQUEST_TIMEOUT = 408;

    /**
     * @Message("Conflict")
     */
    const HTTP_CONFLICT = 409;

    /**
     * @Message("Gone")
     */
    const HTTP_GONE = 410;

    /**
     * @Message("Length Required")
     */
    const HTTP_LENGTH_REQUIRED = 411;

    /**
     * @Message("Precondition Failed")
     */
    const HTTP_PRECONDITION_FAILED = 412;

    /**
     * @Message("Request Entity Too Large")
     */
    const HTTP_REQUEST_ENTITY_TOO_LARGE = 413;

    /**
     * @Message("Request-URI Too Long")
     */
    const HTTP_REQUEST_URI_TOO_LONG = 414;

    /**
     * @Message("Unsupported Media Type")
     */
    const HTTP_UNSUPPORTED_MEDIA_TYPE = 415;

    /**
     * @Message("Requested Range Not Satisfiable")
     */
    const HTTP_REQUESTED_RANGE_NOT_SATISFIABLE = 416;

    /**
     * @Message("Expectation Failed")
     */
    const HTTP_EXPECTATION_FAILED = 417;

    /**
     * @Message("I'm a teapot")
     */
    const HTTP_I_AM_A_TEAPOT = 418;                                               // RFC2324

    /**
     * @Message("Unprocessable Entity")
     */
    const HTTP_UNPROCESSABLE_ENTITY = 422;                                        // RFC4918

    /**
     * @Message("Locked")
     */
    const HTTP_LOCKED = 423;                                                      // RFC4918

    /**
     * @Message("Failed Dependency")
     */
    const HTTP_FAILED_DEPENDENCY = 424;                                           // RFC4918

    /**
     * @Message("Reserved for WebDAV advanced collections expired proposal")
     */
    const HTTP_RESERVED_FOR_WEBDAV_ADVANCED_COLLECTIONS_EXPIRED_PROPOSAL = 425;   // RFC2817

    /**
     * @Message("Upgrade Required")
     */
    const HTTP_UPGRADE_REQUIRED = 426;                                            // RFC2817

    /**
     * @Message("Precondition Required")
     */
    const HTTP_PRECONDITION_REQUIRED = 428;                                       // RFC6585

    /**
     * @Message("Too Many Requests")
     */
    const HTTP_TOO_MANY_REQUESTS = 429;                                           // RFC6585

    /**
     * @Message("Request Header Fields Too Large")
     */
    const HTTP_REQUEST_HEADER_FIELDS_TOO_LARGE = 431;                             // RFC6585

    /**
     * @Message("Internal Server Error")
     */
    const HTTP_INTERNAL_SERVER_ERROR = 500;

    /**
     * @Message("Not Implemented")
     */
    const HTTP_NOT_IMPLEMENTED = 501;

    /**
     * @Message("Bad Gateway")
     */
    const HTTP_BAD_GATEWAY = 502;

    /**
     * @Message("Service Unavailable")
     */
    const HTTP_SERVICE_UNAVAILABLE = 503;

    /**
     * @Message("Gateway Timeout")
     */
    const HTTP_GATEWAY_TIMEOUT = 504;

    /**
     * @Message("HTTP Version Not Supported")
     */
    const HTTP_VERSION_NOT_SUPPORTED = 505;

    /**
     * @Message("Variant Also Negotiates (Experimental)")
     */
    const HTTP_VARIANT_ALSO_NEGOTIATES_EXPERIMENTAL = 506;                        // RFC2295

    /**
     * @Message("Insufficient Handler")
     */
    const HTTP_INSUFFICIENT_STORAGE = 507;                                        // RFC4918

    /**
     * @Message("Loop Detected")
     */
    const HTTP_LOOP_DETECTED = 508;                                               // RFC5842

    /**
     * @Message("Not Extended")
     */
    const HTTP_NOT_EXTENDED = 510;                                                // RFC2774

    /**
     * @Message("Network Authentication Required")
     */
    const HTTP_NETWORK_AUTHENTICATION_REQUIRED = 511;

    /**
     * @Message("Account not authorized")
     */
    const AUTHORIZATION_ACCOUNT_VERIFICATION_FAILED = 10001;

    /**
     * @Message("Account already exists")
     */
    const ACCOUNT_ALREADY_EXISTS = 10002;

    /**
     * @Message("Failed to add user account")
     */
    const ACCOUNT_ADD_ERROR = 10003;

    /**
     * @Message("Account not available")
     */
    const ACCOUNT_NOT_AVAILABLE = 10004;
}
