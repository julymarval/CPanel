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
        'ExistingShowCode'         => 111, 
        'ExistingAdminCode'        => 112,
        'NonExistingSponsorsCode'  => 113,
        'ExistingSponsorCode'      => 114,
        'InvalidTokenCode'         => 202,
        'InvalidCredentialsCode'   => 203,
        'TokenExpiredCode'         => 204,
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
        'NonExistingShowsMsg'      => 'NonExistingShows',
        'ExistingEventMsg'         => 'ExistingEvent',
        'InvalidPasswordMsg'       => 'InvalidPassword',
        'NonExistingEventMsg'      => 'NonExistingEvent',
        'ExistingAdminMsg'         => 'ExistingAdmin',
        'ExistingShowMsg'          => 'ExistingShow',
        'NonExistingSponsorsMsg'   => 'NonExistingSponsors',
        'ExistingSponsorMsg'       => 'ExistingSponsor',
        'InvalidTokenMsg'          => 'InvalidToken',
        'InvalidCredentialsMsg'    => 'InvalidCredentials',
        'TokenExpiredMsg'          => 'TokenExpired',
        'InternalErrorMsg'         => 'InternalError'
    ],
    'tables' => [
        'UsersTable'              => 'users',
        'SalesTable'              => 'sales',
        'VolunteersTable'         => 'volunteers',
        'ShowsTable'              => 'shows',
        'SponsorsTable'           => 'sponsors',
        'EventsTable'             => 'events',
        'Show_Volunteer'          => 'show_volunteer',
        'Event_Volunteer'         => 'event_volunteer'
    ],
    'fields' => [
        'IdField'                 => 'id',
        'NameField'               => 'name',
        'EmailField'              => 'email'
    ]
];


?>