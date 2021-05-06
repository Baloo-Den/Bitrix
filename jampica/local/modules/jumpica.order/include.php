<?php

//Дополнительные классы
CModule::AddAutoloadClasses(
    'jumpica.order',
    [
        'Jumpica\Order\OrderFunction' => 'lib/OrderFunction.php',
        'Jumpica\Order\RestFunction' => 'lib/RestFunction.php',
    ]
);

?>
