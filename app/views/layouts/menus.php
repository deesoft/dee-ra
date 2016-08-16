<?php
return [
    ['label' => 'Home','icon'=>'dashboard', 'items' => [
            ['label' => 'Dashbord', 'url' => ['/site/index']],
            ['label' => 'Login', 'url' => ['/site/login']],
        ]
    ],
    ['label' => 'Purchase', 'items' => [
            ['label' => 'Create', 'url' => ['/purchase/purchase/create']],
            ['label' => 'Index', 'url' => ['/purchase/purchase/index']],
        ]
    ]
];
