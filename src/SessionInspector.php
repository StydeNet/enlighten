<?php

namespace Styde\Enlighten;

use Illuminate\Session\Store;

class SessionInspector
{
    /**
     * @var Store
     */
    private Store $session;

    public function __construct(Store $session)
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
