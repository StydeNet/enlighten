<?php

namespace Styde\Enlighten;

use Illuminate\Session\Store as SessionStore;

class SessionInspector
{
    private SessionStore $session;

    public function __construct(SessionStore $session)
    {
        $this->session = $session;
    }

    public function getData(): array
    {
        $session = $this->session->all();

        if (! empty($session['errors'])) {
            $session['errors'] = collect($session['errors']->getBags());
        }

        return collect($session)->toArray();
    }
}
