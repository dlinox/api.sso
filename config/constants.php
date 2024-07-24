<?php
return [
    'permissions' => [
        [
            'name' => 'dashboard',
            'description' => 'Dashboard',
            'type' => '001',
            'actions' => [],
        ],
        [
            'name' => 'users',
            'description' => 'Usuarios',
            'type' => '001',
            'actions' => [
                [
                    'name' => 'users.update',
                    'description' => 'Usuarios - Actualizar',
                    'type' => '002',
                ],
                [
                    'name' => 'users.create',
                    'description' => 'Usuarios - Crear',
                    'type' => '002',
                ],
                [
                    'name' => 'users.change-password',
                    'description' => 'Usuarios - Cambiar contraseña',
                    'type' => '002',
                ],
                [
                    'name' => 'users.assign-roles',
                    'description' => 'Usuarios - Asignar roles',
                    'type' => '002',
                ]
            ]
        ],
        [
            'name' => 'roles',
            'description' => 'Roles',
            'type' => '001',
            'children' => [],
            'actions' => [
                [
                    'name' => 'roles.update',
                    'description' => 'Roles - Actualizar',
                    'type' => '002',
                ],
                [
                    'name' => 'roles.create',
                    'description' => 'Roles - Crear',
                    'type' => '002',
                ],
                [
                    'name' => 'roles.assign-permissions',
                    'description' => 'Roles - Asignar permisos',
                    'type' => '002',
                ]
            ]
        ],
        [
            'name' => 'students',
            'description' => 'Estudiantes',
            'type' => '001',
            'actions' => [
                [
                    'name' => 'students.update',
                    'description' => 'Estudiantes - Actualizar',
                    'type' => '002',
                ],
                [
                    'name' => 'students.create',
                    'description' => 'Estudiantes - Crear',
                    'type' => '002',
                ],
                [
                    'name' => 'students.search',
                    'description' => 'Estudiantes - Buscar',
                    'type' => '002',
                ]
            ]

        ],
        [
            'name' => 'professors',
            'description' => 'Profesores',
            'type' => '001',
            'actions' => [
                [
                    'name' => 'professors.update',
                    'description' => 'Profesores - Actualizar',
                    'type' => '002',
                ],
                [
                    'name' => 'professors.create',
                    'description' => 'Profesores - Crear',
                    'type' => '002',
                ],
            ]
        ],
        [
            'name' => 'workers',
            'description' => 'Trabajadores',
            'type' => '001',
            'actions' => [
                [
                    'name' => 'workers.update',
                    'description' => 'Trabajadores - Actualizar',
                    'type' => '002',
                ],
                [
                    'name' => 'workers.create',
                    'description' => 'Trabajadores - Crear',
                    'type' => '002',
                ],
            ]
        ],
        [
            'name' => 'externals',
            'description' => 'Externos',
            'type' => '001',
            'actions' => [
                [
                    'name' => 'externals.update',
                    'description' => 'Externos - Actualizar',
                    'type' => '002',
                ],
                [
                    'name' => 'externals.create',
                    'description' => 'Externos - Crear',
                    'type' => '002',
                ],
            ]
        ],
        [
            'name' => 'offices',
            'description' => 'Oficinas',
            'type' => '001',
            'actions' => [
                [
                    'name' => 'offices.update',
                    'description' => 'Oficinas - Actualizar',
                    'type' => '002',
                ],
                [
                    'name' => 'offices.create',
                    'description' => 'Oficinas - Crear',
                    'type' => '002',
                ],
            ]
        ],
        [
            'name' => 'type-attentions',
            'description' => 'Tipos de atención',
            'type' => '001',
            'actions' => [
                [
                    'name' => 'type-attentions.update',
                    'description' => 'Tipos de atención - Actualizar',
                    'type' => '002',
                ],
                [
                    'name' => 'type-attentions.create',
                    'description' => 'Tipos de atención - Crear',
                    'type' => '002',
                ],
            ]
        ],
    ]
];
