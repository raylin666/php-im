<?php

declare(strict_types=1);

namespace App\Dependencies;

use App\Exception\RuntimeException;
use App\Constants\HttpErrorCode;
use App\Helpers\AppHelper;
use Hyperf\HttpMessage\Stream\SwooleStream;
use Hyperf\HttpServer\Response as HttpServerResponse;
use Hyperf\Utils\Arr;

/**
 * Class Response
 * @package App\Dependencies
 */
class Response extends HttpServerResponse
{
    /**
     * RESTfulAPI JSON 格式响应
     *
     * @param null  $data
     * @param int   $status
     * @param array $headers
     * @param null  $message
     * @return object|\Psr\Http\Message\ResponseInterface
     */
    public function RESTfulAPI($data = null, $status = HttpErrorCode::HTTP_OK, array $headers = [], $message = null)
    {
        $status = (int) $status;

        $startResponseTime = Arr::get(AppHelper::getRequest()->getServerParams(), 'request_time_float', 0);
        $endResponseTime = microtime(true) - $startResponseTime;

        $data = $this->toJson([
            'code'              =>      $status,
            'message'           =>      is_null($message) ? HttpErrorCode::getMessage($status) : $message,
            'response_time'     =>      $endResponseTime,
            'data'              =>      $data,
        ]);

        // Http 状态码 > 100 || < 600
        $httpStatus = ($status < 100 || $status >= 600) ? HttpErrorCode::HTTP_OK : $status;

        $response = $this->getResponse()
                    ->withStatus($httpStatus)
                    ->withAddedHeader('content-type', 'application/json; charset=utf-8');

        if ($headers) {
            foreach ($headers as $name => $value) {
                $response->withAddedHeader($name, $value);
            }
        }

        return $response->withBody(new SwooleStream($data));
    }

    /**
     * 抛出错误异常响应
     * @param        $code
     * @param string $msg
     */
    public function error($code, $msg = '')
    {
        throw new RuntimeException(
            $code,
            $msg ? : HttpErrorCode::getMessage($code)
        );
    }

    /**
     * 成功响应数据
     * @param $data
     * @return mixed
     */
    public function success($data = [])
    {
        return $data;
    }
}
