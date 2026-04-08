<?php

return [
    [
        'sl'     => 1,
        'header' => 'Main Navigation',
    ],
    [
        'sl'    => 2,
        'title' => "Dashboard",
        'icon'  => 'bi bi-grid-1x2-fill',
        'url'   => '/admin',
        'role'  => 'admin',
    ],
    [
        'sl'    => 3,
        'title' => "Services",
        'icon'  => 'bi bi-scissors',
        'url'   => '/admin/services',
        'role'  => 'admin',
    ],
    [
        'sl'    => 4,
        'title' => "Products",
        'icon'  => 'bi bi-box-seam',
        'url'   => '/admin/products',
        'role'  => 'admin',
    ],
    [
        'sl'    => 5,
        'title' => "Team",
        'icon'  => 'bi bi-people-fill',
        'url'   => '/admin/team',
        'role'  => 'admin',
    ],
    [
        'sl'    => 6,
        'title' => "Testimonials",
        'icon'  => 'bi bi-chat-square-quote',
        'url'   => '/admin/testimonials',
        'role'  => 'admin',
    ],
    [
        'sl'    => 7,
        'title' => "Gallery",
        'icon'  => 'bi bi-images',
        'url'   => '/admin/gallery',
        'role'  => 'admin',
    ],
    [
        'sl'    => 8,
        'title' => "Contact Messages",
        'icon'  => 'bi bi-envelope-paper',
        'url'   => '/admin/contacts',
        'role'  => 'admin',
    ],
    [
        'sl'     => 10,
        'header' => 'System',
    ],
    [
        'sl'    => 11,
        'title' => "User Management",
        'icon'  => 'bi bi-person-lock',
        'role'  => 'admin',
        'children' => [
            [
                'title' => "Roles",
                'url'   => '/authorization/roles',
            ],
            [
                'title' => "Users",
                'url'   => '/authorization/users',
            ],
        ]
    ],
    [
        'sl'    => 12,
        'title' => "Settings",
        'icon'  => 'bi bi-gear',
        'url'   => '/settings',
        'role'  => 'admin',
    ],
];
