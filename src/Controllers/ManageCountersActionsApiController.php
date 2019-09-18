<?php

namespace Modules\Opx\Counters\Controllers;

use Core\Http\Controllers\Controller;
use Illuminate\Database\Eloquent\Builder as EloquentBuilder;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Exception;
use Modules\Opx\Counters\Models\Counter;

class ManageCountersActionsApiController extends Controller
{
    /**
     * Delete counters with given ids.
     *
     * @param Request $request
     * @return JsonResponse
     *
     * @throws Exception
     */
    public function postDelete(Request $request): JsonResponse
    {
        $ids = $request->all();

        /** @var EloquentBuilder $counters */
        $counters = Counter::query()->whereIn('id', $ids)->get();

        if ($counters->count() > 0) {
            /** @var Counter $counter */
            foreach ($counters as $counter) {
                $counter->delete();
            }
        }

        return response()->json(['message' => 'success']);
    }

    /**
     * Restore counters with given ids.
     *
     * @param Request $request
     *
     * @return JsonResponse
     */
    public function postRestore(Request $request): JsonResponse
    {
        $ids = $request->all();

        /** @var EloquentBuilder $counters */
        $counters = Counter::query()->whereIn('id', $ids)->onlyTrashed()->get();

        if ($counters->count() > 0) {
            /** @var Counter $counter */
            foreach ($counters as $counter) {
                $counter->restore();
            }
        }

        return response()->json(['message' => 'success']);
    }

    /**
     * Enable counters with given ids.
     *
     * @param Request $request
     *
     * @return  JsonResponse
     */
    public function postEnable(Request $request): JsonResponse
    {
        $ids = $request->all();

        /** @var EloquentBuilder $counters */
        $counters = Counter::query()->whereIn('id', $ids)->get();

        if ($counters->count() > 0) {
            /** @var Counter $counter */
            foreach ($counters as $counter) {
                if (!(bool)$counter->getAttribute('enabled')) {
                    $counter->setAttribute('enabled', true);
                    $counter->save();
                }
            }
        }

        return response()->json(['message' => 'success']);
    }

    /**
     * Disable counters with given ids.
     *
     * @param Request $request
     *
     * @return  JsonResponse
     */
    public function postDisable(Request $request): JsonResponse
    {
        $ids = $request->all();

        /** @var EloquentBuilder $counters */
        $counters = Counter::query()->whereIn('id', $ids)->get();

        if ($counters->count() > 0) {
            /** @var Counter $counter */
            foreach ($counters as $counter) {
                if ((bool)$counter->getAttribute('enabled')) {
                    $counter->setAttribute('enabled', false);
                    $counter->save();
                }
            }
        }

        return response()->json([
            'message' => 'success',
        ]);
    }
}