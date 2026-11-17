<?php
require_once __DIR__ . '/CategoryService.php';
require_once __DIR__ . '/RegistrationService.php';

/** Run one action and print OK or the exception it threw. */
function attempt($description, $action)
{
    echo str_pad($description, 45, '.') . ' ';
    try {
        $result = $action();
        echo "OK" . (isset($result['id']) ? " (id {$result['id']})" : '') . "\n";
        return $result;
    } catch (HttpException $e) {
        echo get_class($e) . " [{$e->get_status()}]: {$e->getMessage()}\n";
    }
}

$categories = new CategoryService();
$registrations = new RegistrationService();

attempt('Category without a name', fn() => $categories->add(['description' => 'x']));
attempt('Duplicate category name', fn() => $categories->add(['name' => 'Music']));
attempt('Delete a category with events', fn() => $categories->delete(1));
attempt('Get category #999', fn() => $categories->get_by_id(999));
attempt('Ema registers a 4th time', fn() => $registrations->register(2, 5));
attempt('Tarik registers for a sold-out event', fn() => $registrations->register(3, 7));
