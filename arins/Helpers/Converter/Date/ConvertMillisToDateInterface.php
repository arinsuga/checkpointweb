<?php
namespace Arins\Helpers\Converter\Date;

interface ConvertMillisToDateInterface
{
    /**
     * ======================================================
     * 1. Date Standard 1 Methods
     * ====================================================== */
    public function millisToDatetime($data, $offset = 0);
}
