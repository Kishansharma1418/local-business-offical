<?php

namespace App\Traits;

use App\Helpers\Logger;

trait Loggable
{
    protected static function bootLoggable()
    {
        static::created(function ($model) {
            Logger::log(
                class_basename($model),
                'Created',
                $model->id,
                null,
                $model->getAttributes(),
                '1'
            );
        });

        static::updating(function ($model) {
            Logger::log(
                class_basename($model),
                'Updated',
                $model->id,
                $model->getOriginal(),
                $model->getDirty(),
                '1'
            );
        });

        static::deleted(function ($model) {
            Logger::log(
                class_basename($model),
                'Deleted',
                $model->id,
                $model->getAttributes(),
                null,
                '1'
            );
        });
    }
}
