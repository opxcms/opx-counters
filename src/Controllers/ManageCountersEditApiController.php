<?php

namespace Modules\Opx\Counters\Controllers;

use Core\Foundation\Templater\Templater;
use Core\Http\Controllers\APIFormController;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Modules\Opx\Counters\Models\Counter;
use Modules\Opx\Counters\OpxCounters;

class ManageCountersEditApiController extends APIFormController
{
    public $addCaption = 'opx_counters::manage.add_counter';
    public $editCaption = 'opx_counters::manage.edit_counter';
    public $create = 'manage/api/module/opx_counters/counters_edit/create';
    public $save = 'manage/api/module/opx_counters/counters_edit/save';
    public $redirect = '/counters/edit/';

    /**
     * Make counter add form.
     *
     * @param Request $request
     *
     * @return  JsonResponse
     */
    public function getAdd(Request $request): JsonResponse
    {
        $template = new Templater(OpxCounters::getTemplateFileName('counter.php'));

        $template->fillDefaults();

        return $this->responseFormComponent(0, $template, $this->addCaption, $this->create);
    }

    /**
     * Make counter edit form.
     *
     * @param Request $request
     *
     * @return  JsonResponse
     */
    public function getEdit(Request $request): JsonResponse
    {
        /** @var Counter $counter */
        $id = $request->input('id');
        $counter = Counter::where('id', $id)->firstOrFail();

        $template = $this->makeTemplate($counter, 'counter.php');

        return $this->responseFormComponent($id, $template, $this->editCaption, $this->save);
    }

    /**
     * Create new category.
     *
     * @param Request $request
     *
     * @return  JsonResponse
     */
    public function postCreate(Request $request): JsonResponse
    {
        $template = new Templater(OpxCounters::getTemplateFileName('counter.php'));

        $template->resolvePermissions();

        $template->fillValuesFromRequest($request);

        if (!$template->validate()) {
            return $this->responseValidationError($template->getValidationErrors());
        }

        $values = $template->getEditableValues();

        $counter = $this->updateCounterData(new Counter(), $values);

        // Refill template
        $template = $this->makeTemplate($counter, 'counter.php');

        return $this->responseFormComponent($counter->getAttribute('id'), $template, $this->editCaption, $this->save, $this->redirect . $counter->getAttribute('id'));
    }

    /**
     * Save counter.
     *
     * @param Request $request
     *
     * @return  JsonResponse
     */
    public function postSave(Request $request): JsonResponse
    {
        /** @var Counter $counter */
        $id = $request->input('id');

        $counter = Counter::withTrashed()->where('id', $id)->firstOrFail();

        $template = new Templater(OpxCounters::getTemplateFileName('counter.php'));

        $template->resolvePermissions();

        $template->fillValuesFromRequest($request);

        if (!$template->validate()) {
            return $this->responseValidationError($template->getValidationErrors());
        }

        $values = $template->getEditableValues();

        $counter = $this->updateCounterData($counter, $values);

        // Refill template
        $template = $this->makeTemplate($counter, 'counter.php');

        return $this->responseFormComponent($id, $template, $this->editCaption, $this->save);
    }

    /**
     * Fill template with data.
     *
     * @param string $filename
     * @param Counter $counter
     *
     * @return  Templater
     */
    protected function makeTemplate(Counter $counter, $filename): Templater
    {
        $template = new Templater(OpxCounters::getTemplateFileName($filename));

        $template->fillValuesFromObject($counter);

        return $template;
    }

    /**
     * Update counter data
     *
     * @param Counter $counter
     * @param array $data
     *
     * @return  Counter
     */
    protected function updateCounterData(Counter $counter, array $data): Counter
    {
        $this->setAttributes($counter, $data, [
            'name', 'enabled', 'content',
        ]);

        $counter->save();

        return $counter;
    }
}