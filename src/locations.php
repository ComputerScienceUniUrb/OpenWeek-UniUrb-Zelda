<?php
/*
 * CodeMOOC TreasureHuntBot
 * ===================
 * UWiClab, University of Urbino
 * ===================
 * Hard-coded locations.
 */

const LOCATION_START_MERCATALE      = '2018-mercatale';
const LOCATION_START_STALUCIA       = '2018-santalucia';
const LOCATION_VOLPONI              = '2018-volponi';
const LOCATION_VOLPONI_EXIT         = '2018-volponi-exit';
const LOCATION_INFORMATICA          = '2018-infoappl';
const LOCATION_TRIDENTE             = '2018-tridente';
const LOCATION_SELFIE               = '2018-selfie';
const LOCATION_END_VELA             = '2018-la-vela';

const LOCATION_ENDING               = LOCATION_END_VELA;

const LOCATION_CODE_MAP             = array(
    'borgomercatale'                => LOCATION_START_MERCATALE,
    'santalucia'                    => LOCATION_START_STALUCIA,
    'register'                      => LOCATION_VOLPONI,
    'volponiexit'                   => LOCATION_VOLPONI_EXIT,
    'd9Ua9NvL'                      => LOCATION_INFORMATICA,
    'tridente'                      => LOCATION_TRIDENTE,
    'selfie'                        => LOCATION_SELFIE,
    'vela'                          => LOCATION_END_VELA
);

const LOCATION_TEXT_MAP        = array(
    LOCATION_START_MERCATALE        => 'TEXT_LOCATION_MERCATALE',
    LOCATION_START_STALUCIA         => 'TEXT_LOCATION_STALUCIA',
    LOCATION_VOLPONI                => 'TEXT_LOCATION_VOLPONI',
    LOCATION_VOLPONI_EXIT           => 'TEXT_LOCATION_VOLPONI_EXIT',
    LOCATION_INFORMATICA            => 'TEXT_LOCATION_INFORMATICA',
    LOCATION_TRIDENTE               => 'TEXT_LOCATION_TRIDENTE',
    LOCATION_SELFIE                 => 'TEXT_LOCATION_SELFIE',
    LOCATION_END_VELA               => 'TEXT_LOCATION_END_VELA'
);

const LOCATION_SELFIE_ARRAY         = array(
    LOCATION_INFORMATICA,
    LOCATION_SELFIE
);

const LOCATION_SELFIE_FILENAME_MAP  = array(
    LOCATION_INFORMATICA            => 'badge-infoapp',
    LOCATION_SELFIE                 => 'badge',
    LOCATION_END_VELA               => 'badge-catchup'
);
