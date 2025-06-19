<?php

namespace Arins\Helpers\Converter\Date;

use Carbon\Carbon;

trait ConvertMillisToDate
{
    /**
     * ======================================================
     * 1. Date Standard 3 Methods
     * ====================================================== */
    public function millisToDatetime($data, $offset = 0)
    {

        $millis = $data + ($offset * 3600000);

        return Carbon::createFromTimestampMs($millis);
    }


}
