<?php

namespace Modules\Opx\Counters;

use Core\Foundation\Module\BaseModule;
use Modules\Opx\Counters\Models\Counter;

class Counters extends BaseModule
{
    /** @var string  Module name */
    protected $name = 'opx_counter';

    /** @var string  Module path */
    protected $path = __DIR__;

    /**
     * Get Counter assigned to key.
     *
     * @return  string|null
     */
    public function all(): ?string
    {
        if ($this->app->environment() !== 'production') {
            return null;
        }

        $counters = Counter::where('enabled', 1)->get();

        if ($counters === null) {
            return null;
        }

        $rendered = '';

        foreach ($counters as $counter) {
            /** @var Counter $counter */
            $rendered .= "<!-- {$counter->getAttribute('name')} -->\r\n";
            $rendered .= $counter->getAttribute('content') . "\r\n";
        }

        return $rendered;
    }
}
