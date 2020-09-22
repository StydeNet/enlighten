<?php

namespace Styde\Enlighten;

use Closure;

class ExampleGeneratorMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $response = $next($request);

        // Allow users to create a whitelist or blacklist of status...
        // @TODO: Add excluded status to a configuration option
        if (app()->runningUnitTests() && ! in_array($response->status(), [404, 500, '...'])) {
            $generator = new ExampleGenerator($request, $response);
            $generator->generateExample();
        }

        return $response;
    }
}
