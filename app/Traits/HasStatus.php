<?php

namespace App\Traits;

use App\Status;

trait HasStatus
{
    public function status()
    {
        return $this->belongsTo(Status::class)->where('model_type', get_class($this));
    }

    public function setStatus( $status )
    {
    	$status = Status::where('model_type', get_class($this))->where('slug', $status)->first();

    	if(!$status) return false;
    	return $this->status()->associate($status);
    }

    public static function statusIs($status = null)
    {
        return self::WhereHas('status', function($q) use ($status) {
            $q->where('name', $status);
        });
    }
}