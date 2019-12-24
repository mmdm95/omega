<?php
defined('BASE_PATH') OR exit('No direct script access allowed');

defined('AUTH_ROLE_SUPER_USER') OR define('AUTH_ROLE_SUPER_USER', 1);
defined('AUTH_ROLE_ADMIN') OR define('AUTH_ROLE_ADMIN', 2);
defined('AUTH_ROLE_WRITER') OR define('AUTH_ROLE_WRITER', 3);
defined('AUTH_ROLE_USER') OR define('AUTH_ROLE_USER', 4);
defined('AUTH_ROLE_GUEST') OR define('AUTH_ROLE_GUEST', 5);

return array(
    'tables' => [
        /*
         * Like:
         *
         * 'outTableName' => 'yourTableName',
         * ...
         *
        */
        'user' => 'users',
        'role' => 'roles',
        'permission' => 'permissions',
        'page' => 'pages',
        'user_role' => 'users_roles',
        'role_page_perm' => 'roles_pages_perms',
        'user_page_perm' => 'users_pages_perms'
    ],
    'columns' => [
        /*
         * Like:
         *
         * 'ourTableName' => [
         *   'ourColumnName' => [
         *      'column' => 'columnName',
         *      'type' => 'columnType'
         *   ], ...
         * ], ...
         *
         */
        'user' => [
            'id' => [
                'column' => 'id',
                'type' => 'INT(10) UNSIGNED NOT NULL PRIMARY KEY AUTO_INCREMENT'
            ],
            // In this site username is mobile number
            'username' => [
                'column' => 'username',
                'type' => 'VARCHAR(255)'
            ],
            'password' => [
                'column' => 'password',
                'type' => 'VARCHAR(128)'
            ],
            'ip_address' => [
                'column' => 'ip_address',
                'type' => 'VARCHAR(45)'
            ],
            'email' => [
                'column' => 'email',
                'type' => 'VARCHAR(255)'
            ],
            'activation_code' => [
                'column' => 'activation_code',
                'type' => 'VARCHAR(255)'
            ],
            'forgotten_password_code' => [
                'column' => 'forgotten_password_code',
                'type' => 'VARCHAR(255)'
            ],
            'forgotten_password_time' => [
                'column' => 'forgotten_password_time',
                'type' => 'int(11)'
            ],
            'created_on' => [
                'column' => 'created_on',
                'type' => 'int(11)'
            ],
            'active' => [
                'column' => 'active',
                'type' => 'tinyint(1)'
            ],
            'first_name' => [
                'column' => 'first_name',
                'type' => 'VARCHAR(50)'
            ],
            'last_name' => [
                'column' => 'last_name',
                'type' => 'VARCHAR(50)'
            ],
            'image' => [
                'column' => 'image',
                'type' => 'VARCHAR(100)'
            ],
            'n_code' => [
                'column' => 'n_code',
                'type' => 'VARCHAR(10)'
            ],
            'card_number' => [
                'column' => 'card_number',
                'type' => 'VARCHAR(20)'
            ]
        ],
        'role' => [
            'id' => [
                'column' => 'id',
                'type' => 'INT(10) UNSIGNED NOT NULL PRIMARY KEY AUTO_INCREMENT'
            ],
            'name' => [
                'column' => 'name',
                'type' => 'VARCHAR(20)'
            ],
            'description' => [
                'column' => 'description',
                'type' => 'VARCHAR(100)'
            ]
        ],
        'permission' => [
            'id' => [
                'column' => 'id',
                'type' => 'INT(10) UNSIGNED NOT NULL PRIMARY KEY AUTO_INCREMENT'
            ],
            'description' => [
                'column' => 'description',
                'type' => 'VARCHAR(100)'
            ]
        ],
        'page' => [
            'id' => [
                'column' => 'id',
                'type' => 'INT(10) UNSIGNED NOT NULL PRIMARY KEY AUTO_INCREMENT'
            ],
            'name' => [
                'column' => 'name',
                'type' => 'VARCHAR(20)'
            ]
        ],
        'user_role' => [
            'user_id' => [
                'column' => 'user_id',
                'type' => 'INT(10) UNSIGNED NOT NULL'
            ],
            'role_id' => [
                'column' => 'role_id',
                'type' => 'INT(10) UNSIGNED NOT NULL'
            ],
            'constraint' => 'ADD CONSTRAINT fk_urp_u FOREIGN KEY(user_id) REFERENCES users(id), ' .
                'ADD CONSTRAINT fk_urp_r FOREIGN KEY(role_id) REFERENCES roles(id)'
        ],
        'role_page_perm' => [
            'role_id' => [
                'column' => 'role_id',
                'type' => 'INT(10) UNSIGNED NOT NULL'
            ],
            'page_id' => [
                'column' => 'page_id',
                'type' => 'INT(10) UNSIGNED NOT NULL'
            ],
            'perm_id' => [
                'column' => 'perm_id',
                'type' => 'INT(10) UNSIGNED NOT NULL'
            ],
            'constraint' => 'ADD CONSTRAINT fk_rpp_r FOREIGN KEY(role_id) REFERENCES roles(id), ' .
                'ADD CONSTRAINT fk_rpp_pa FOREIGN KEY(page_id) REFERENCES pages(id), ' .
                'ADD CONSTRAINT fk_rpp_p FOREIGN KEY(perm_id) REFERENCES permissions(id)'
        ],
        'user_page_perm' => [
            'user_id' => [
                'column' => 'user_id',
                'type' => 'INT(10) UNSIGNED NOT NULL'
            ],
            'page_id' => [
                'column' => 'page_id',
                'type' => 'INT(10) UNSIGNED NOT NULL'
            ],
            'perm_id' => [
                'column' => 'perm_id',
                'type' => 'INT(10) UNSIGNED NOT NULL'
            ],
            'allow' => [
                'column' => 'allow',
                'type' => 'INT(1) UNSIGNED NOT NULL DEFAULT 0'
            ],
            'constraint' => 'ADD CONSTRAINT fk_upp_u FOREIGN KEY(user_id) REFERENCES users(id), ' .
                'ADD CONSTRAINT fk_upp_pa FOREIGN KEY(page_id) REFERENCES pages(id), ' .
                'ADD CONSTRAINT fk_upp_pe FOREIGN KEY(perm_id) REFERENCES permissions(id)'
        ]
    ],
    'pages' => [
    ],
    'roles' => [
        'superUser', 'admin', 'writer', 'user', 'guest'
    ],
    'permissions' => [
        'create', 'read', 'update', 'delete'
    ],

    // For advanced usage.

    // Use sub array from roles option
    'admin_roles' => ['superUser', 'admin', 'writer'],
);
