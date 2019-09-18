<?php

namespace Modules\Opx\Counters;

use Illuminate\Support\Facades\Facade;

/**
 * @method  static string|null  all()
 * @method  static string getTemplateFileName(string $name)
 */
class OpxCounters extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor(): string
    {
        return 'opx_counters';
    }
}
