<?php

return [
    'codes' => [
        'OkCode'                   => 0,
        'MissingInputCode'         => 100,
        'InvalidInputCode'         => 101,
        'NonExistingAdminCode'     => 102,
        'ExistingVolunteerCode'    => 103,
        'NonExistingVolunteerCode' => 104,
        'NonExistingSalesCode'     => 105,
        'ExistingSaleCode'         => 106,
        'NonExistingShowsCode'     => 107,
        'ExistingEventCode'        => 108,
        'InvalidPasswordCode'      => 109,
        'NonExistingEventCode'     => 110, 
        'InvalidTokenCode'         => 202,
        'InternalErrorCode'        => 999
    ],
    'msgs' => [
        'OkMsg'                    => 'Ok',
        'MissingInputMsg'          => 'MissingInputParameter',
        'InvalidInputMsg'          => 'InvalidInputParameter',
        'NonExistingAdminMsg'      => 'NonExistingAdmin',
        'ExistingVolunteerMsg'     => 'ExistingVolunteer',
        'NonExistingVolunteerMsg'  => 'NonExistingVolunteer',
        'NonExistingSalesMsg'      => 'NonExistingSales',
        'ExistingSaleMsg'          => 'ExistingSale',
        'NonExistingShowsMgs'      => 'NonExistingShows',
        'ExistingEventMsg'         => 'ExistingEvent',
        'InvalidPasswordMsg'       => 'InvalidPassword',
        'NonExistingEventMsg'      => 'NonExistingEvent',
        'InvalidTokenMsg'          => 'InvalidToken',
        'InternalErrorMsg'         => 'InternalError'
    ],
    'tables' => [
        'UsersTable'              => 'users',
        'SalesTable'              => 'sales',
        'VolunteersTable'         => 'volunteers',
        'ShowsTable'              => 'shows',
        'SponsorsTable'           => 'sponsors',
        'EventsTable'             => 'events'
    ],
    'fields' => [
        'UserIdField'             => 'user_id',
        'SalesIdField'            => 'sale_id',
        'VolunteersIdField'       => 'volunteer_id',
        'ShowsIdField'            => 'show_id',
        'SponsorsIdField'         => 'sponsor_id',
        'EventsIdField'           => 'event_id',
        'NameField'               => 'name',
        'EmailField'              => 'email'
    ]
];


?>