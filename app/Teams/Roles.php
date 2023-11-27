<?php

namespace App\Teams;

class Roles
{
    public static $roleWhenCreatingTeam = 'team_admin';

    public static $roleWhenJoiningTeam = 'team_member';

    public static $roles = [
        'team_admin' => [
            'name' => 'Admin',
            'permissions' => [
                'view team dashboard',
                'manage team subscription',
                'delete team',
                'delete users',
                'change user roles',
                'add users'
            ]
        ],

        'team_member' => [
            'name' => 'Member',
            'permissions' => [
                'view team dashboard'
            ]
        ]
    ];
}
