<?php

require_once 'chargeio-php/lib/ChargeIO.php';

// live
//ChargeIO::setCredentials(new ChargeIO_Credentials('<public>', '<secret>'));

// test
ChargeIO::setCredentials(new ChargeIO_Credentials('<public>', '<secret>'));
