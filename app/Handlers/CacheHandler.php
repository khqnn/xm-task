<?php


class CacheHandler extends BaseHandler
{

    private BaseHandler $handler;
    private $cacheKey;

    public function __construct(BaseHandler $handler, $cache_key)
    {
        $this->handler = $handler;
        $this->cacheKey = $cache_key;
    }


    public function handle($params)
    {


        $cache_key = $this->cacheKey;

        if (!$cache_data = cache($cache_key)) {
            $cache_data = $this->handler->handle($params);

            if (!$cache_data['success']) {
                return $cache_data;
            }

            $cache_data = $cache_data['data'][$cache_key];
            cache()->save($cache_key, $cache_data);
        }


        $params[$cache_key] = $cache_data;

        $nextHandleResponse = $this->callNextHandler($params);
        $data = $nextHandleResponse['data'];
        $data[$cache_key] = $cache_data;
        $nextHandleResponse['data'] = $data;
        return $nextHandleResponse;
    }
}
