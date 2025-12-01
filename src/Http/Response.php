<?php

namespace ShufflingPixels\NotificationAction\Http;

use Illuminate\Http\Response as HttpResponse;

class Response
{
    public function __construct(
        public ?HttpResponse $httpResponse = null,
        public bool $markAsRead = false)
    {
        $this->httpResponse ??= response()->noContent(200);
    }

    static public function markAsRead()
    {
        return new Response(markAsRead: true);
    }
}
