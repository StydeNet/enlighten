<?php

namespace Styde\Enlighten;

use Closure;

class HttpExampleGeneratorMiddleware
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
        // @TODO: Add ignored statuses to a configuration option
        if (app()->runningUnitTests() && $this->allowedStatus($response)) {
            app(HttpExampleGenerator::class)->createHttpExample($request, $response);
        }

        return $response;
    }

    protected function allowedStatus($response): bool
    {
        return ! collect(config('enlighten.response.status.ignore'))->contains($response->status());
    }
}
