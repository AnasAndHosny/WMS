<?php

namespace App\Helpers;

use Exception;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class ExceptionHelper
{
    public static function throwModelNotFound(Model $model): Exception
    {
        $exception = new ModelNotFoundException();
        $exception->setModel(get_class($model), [$model->getRouteKey()]);
        throw $exception;
    }
}
