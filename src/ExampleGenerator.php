<?php

namespace Styde\Enlighten;

use Illuminate\Http\Request;
use Illuminate\Session\Store as SessionStore;
use Symfony\Component\HttpFoundation\Response;

// @TODO: rename class because it's not generating anything anymore.
// ExampleRepository? ExampleRecorder?
class ExampleGenerator
{
    private TestInspector $testInspector;
    private RequestInspector $requestInspector;
    private ResponseInspector $responseInspector;
    private SessionStore $session;

    public function __construct(
        TestInspector $testInspector,
        RequestInspector $requestInspector,
        ResponseInspector $responseInspector,
        SessionStore $session
    ) {
        $this->testInspector = $testInspector;
        $this->requestInspector = $requestInspector;
        $this->responseInspector = $responseInspector;
        $this->session = $session;
    }

    public function generateExample(Request $request, Response $response)
    {
        $testMethodInfo = $this->testInspector->getInfo();

        if ($testMethodInfo->isExcluded()) {
            return;
        }

        $testMethodInfo->save(
            $this->requestInspector->getInfoFrom($request),
            $this->responseInspector->getInfoFrom($response),
            $this->getNormalizedSession()
        );
    }

    private function getNormalizedSession()
    {
        $session = $this->session->all();

        if (! empty($session['errors'])) {
            $session['errors'] = collect($session['errors']->getBags());
        }

        return collect($session)->toArray();
    }
}
