<?php

declare(strict_types=1);

return [
	'routes' => [
		['name' => 'settings#save', 'url' => '/settings', 'verb' => 'POST'],
		['name' => 'settings#load', 'url' => '/settings', 'verb' => 'GET'],
	],
];
