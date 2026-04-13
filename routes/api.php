<?php

foreach (glob(__DIR__.'/modules/api/*.php') as $routeFile) {
    require $routeFile;
}
