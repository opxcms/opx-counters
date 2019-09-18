<?php

namespace Modules\Opx\Counters\Controllers;

use Illuminate\Database\Eloquent\Builder as EloquentBuilder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\JsonResponse;
use Core\Http\Controllers\APIListController;
use Modules\Opx\Counters\Models\Counter;

class ManageCountersListApiController extends APIListController
{
    protected $caption = 'opx_counters::manage.counters';
    protected $description;
    protected $source = 'manage/api/module/opx_counters/counters_list/counters';


    protected $enable = 'manage/api/module/opx_counters/counters_actions/enable';
    protected $disable = 'manage/api/module/opx_counters/counters_actions/disable';
    protected $delete = 'manage/api/module/opx_counters/counters_actions/delete';
    protected $restore = 'manage/api/module/opx_counters/counters_actions/restore';

    protected $add = 'opx_counters::counters_add';
    protected $edit = 'opx_counters::counters_edit';

    /**
     * Get list of users with sorting, filters and search.
     *
     * @return  JsonResponse
     */
    public function postCounters(): JsonResponse
    {
        $counters = $this->makeQuery()->get();

        /** @var Collection $counters */
        if ($counters->count() > 0) {
            $counters->transform(function ($counter) {
                /** @var Counter $counter */
                return $this->makeListRecord(
                    $counter->getAttribute('id'),
                    $counter->getAttribute('name'),
                    null,
                    null,
                    null,
                    (bool)$counter->getAttribute('enabled'),
                    $counter->getAttribute('deleted_at') !== null
                );
            });
        }

        $response = ['data' => $counters->toArray()];

        return response()->json($response);
    }

    /**
     * Make base list query.
     *
     * @return  EloquentBuilder
     */
    protected function makeQuery(): EloquentBuilder
    {
        /** @var EloquentBuilder $query */
        $query = Counter::query()->withTrashed();

        return $query;
    }
}