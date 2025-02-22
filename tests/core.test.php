<?php

use Leaf\Router;

test('static call', function () {
	expect(Router::routes())->toBeArray();
});

test('set 404', function () {
	$r0 = new Router;
	$r0->set404(function () {
		echo '404';
	});

	ob_start();
	$r0->run();

	expect(ob_get_contents())->toBe('404');
	ob_end_clean();
});

test('set down', function () {
	$router = new Router;
	$router->configure(['app.down' => true]);

	$router->setDown(function () {
		echo 'down';
	});

	ob_start();
	$router->run();

	expect(ob_get_contents())->toBe('down');
	ob_end_clean();

	// clean up
	$router->configure(['app.down' => false]);
});

test('get route data', function () {
	$_SERVER['REQUEST_METHOD'] = 'GET';
	$_SERVER['REQUEST_URI'] = '/thispath';

	$lr = new Router;

	$lr->get('/thispath', ['name' => 'thisroutename', function () use($lr) {
		echo json_encode($lr->getRoute());
	}]);

	ob_start();
	$lr->run();

	$data = json_decode(ob_get_contents(), true);

	expect($data['path'])->toBe('/thispath');
	expect($data['name'])->toBe('thisroutename');
	expect($data['method'])->toBe('GET');

	ob_end_clean();
});
