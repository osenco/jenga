<?php

function setup_finserve($config)
{
    return \Osen\Finserve\Equity::init($config);
}