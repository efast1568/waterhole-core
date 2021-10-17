<?php

namespace Waterhole\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Waterhole\Actions\Action;
use Waterhole\Extend;

class ActionController extends Controller
{
    protected function getItems(Request $request)
    {
        abort_unless($class = Extend\Actionable::getItems()[$request->get('actionable')] ?? null, 400);

        return $class::query()->findMany((array) $request->get('id'));
    }

    protected function getAction($items, Request $request): Action
    {
        $actions = Extend\Action::for($items);
        $requestedAction = $request->get('action_class');

        foreach ($actions as $action) {
            if (get_class($action) === $requestedAction) {
                return $action;
            }
        }

        abort(403, 'This action cannot be applied.');
    }

    public function confirm(Request $request)
    {
        $items = $this->getItems($request);
        $action = $this->getAction($items, $request);
        $confirmation = $action->confirmation($items);
        $confirmationBody = $action->confirmationBody($items);

        return view('waterhole::confirm-action', [
            'confirmation' => $confirmation,
            'confirmationBody' => $confirmationBody,
            'action' => $action,
            'actionable' => $request->get('actionable'),
            'items' => $items,
        ]);
    }

    public function run(Request $request)
    {
        $items = $this->getItems($request);
        $action = $this->getAction($items, $request);

        if ($action->confirm && ! $request->has('confirmed')) {
            return redirect()->route('waterhole.action.create', $request->input());
        }

        try {
            if ($response = $action->run($items, $request)) {
                return $response;
            }
        } catch (ValidationException $exception) {
            throw $exception->redirectTo(route('waterhole.action.create', $request->input()));
        }

        return redirect($request->get('return', url()->previous()));
    }
}
