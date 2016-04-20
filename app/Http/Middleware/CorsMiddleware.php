<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Response;
use Lukasoppermann\Httpstatus\Httpstatuscodes;

class CorsMiddleware
{
    public function handle($request, Closure $next)
    {
        $response = $next($request);

        // add allow method header if not exist
        if ($this->allowMethodHeaderExists() === false) {
            $response->header('Access-Control-Allow-Methods', $request->getMethod());
        }

        if ($request->getMethod() == 'OPTIONS' && $response->getStatusCode() == 405) {

            $response->header('Access-Control-Allow-Methods', ['GET','POST','PATCH','DELETE','OPTIONS']);

            return new Response('', 204, $response->headers->all());
        }

        return $response;
    }
    /**
     * Find the Access-Control-Allow-Methods: in header_list()
     *
     * @method allowMethodHeaderExists
     *
     * @return [bool]
     */
    private function allowMethodHeaderExists()
    {
        return strpos(implode(' ', headers_list()), 'Access-Control-Allow-Methods:') !== false;
    }
}
