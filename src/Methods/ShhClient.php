<?php

namespace Larathereum\Methods;

class ShhClient extends AbstractMethods
{
    public function version(): string
    {
        $response = $this->client->send(
            $this->client->request(67, 'shh_version', [])
        );

        return $response->getRpcResult();
    }

    // TODO: missing methods
}
