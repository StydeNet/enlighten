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
        if (app()->runningUnitTests() && $this->allowedStatus($response)) {
            app(ExampleGenerator::class)->generateExample($request, $response);
        }

        return $response;
    }

    protected function allowedStatus($response): bool
    {
        return !in_array($response->status(), [404, 500, '...']);
    }
}
