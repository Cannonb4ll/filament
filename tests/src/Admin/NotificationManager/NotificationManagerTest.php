<?php

use Filament\Tests\Admin\Fixtures\Pages\Settings;
use Filament\Tests\Admin\NotificationManager\TestCase;
use Illuminate\Support\Facades\Session;
use Livewire\LivewireManager;
use function Pest\Livewire\livewire;

uses(TestCase::class);

it('can immediately emit dispatchNewNotifications event', function () {
    $component = livewire(Settings::class);

    LivewireManager::$isLivewireRequestTestingOverride = true;

    $component
        ->call('notificationManager')
        ->assertEmitted('dispatchNewNotifications');

    expect(Session::get('filament.notifications'))->toBeEmpty();
});

it('will not emit dispatchNewNotifications event if Livewire component redirects', function () {
    livewire(Settings::class)
        ->call('notificationManager', redirect: true)
        ->assertNotEmitted('dispatchNewNotifications');

    expect(Session::get('filament.notifications'))
        ->toBeArray()
        ->toHaveLength(1)
        ->sequence(
            fn ($notification) => $notification->title->toContain('Saved!')
        );
});
