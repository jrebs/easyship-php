<?php

namespace Easyship;

abstract class Module
{
    /** @var string */
    const API_VERSION = '2023-01';

    /**
     * @var \Easyship\EasyshipAPI
     */
    protected $easyship;

    /**
     * Instantiate an Easyship API Module
     *
     * @param \Easyship\EasyshipAPI $easyship
     */
    public function __construct(EasyshipAPI $easyship)
    {
        $this->easyship = $easyship;
    }
}
