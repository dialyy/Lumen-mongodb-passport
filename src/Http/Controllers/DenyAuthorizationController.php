<?php

namespace Luqta\LaravelPassportMongoDB\Http\Controllers;

use Illuminate\Support\Arr;
use Illuminate\Http\Request;
use Illuminate\Contracts\Routing\ResponseFactory;

class DenyAuthorizationController
{
    use RetrievesAuthRequestFromSession;

    /**
     * The response factory implementation.
     *
     * @var \Illuminate\Contracts\Routing\ResponseFactory
     */
    protected $response;

    /**
     * Create a new controller instance.
     *
     * @param  \Illuminate\Contracts\Routing\ResponseFactory  $response
     * @return void
     */
    public function __construct(ResponseFactory $response)
    {
        $this->response = $response;
    }

    /**
     * Deny the authorization request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function deny(Request $request)
    {
        $authRequest = $this->getAuthRequestFromSession($request);

        if (is_array($uri = $authRequest->getClient()->getRedirectUri())) {
            $uri = Arr::first($uri);
        }

        $separator = $authRequest->getGrantTypeId() === 'implicit' ? '#' : '?';

        return $this->response->redirectTo(
            $uri.$separator.'error=access_denied&state='.$request->input('state')
        );
    }
}
