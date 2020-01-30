<?php

namespace Kouloughli\UserActivity\Tests\Unit\Listeners;

use Kouloughli\Events\Role\Created;
use Kouloughli\Events\Role\Deleted;
use Kouloughli\Events\Role\PermissionsUpdated;
use Kouloughli\Events\Role\Updated;

class RoleEventsSubscriberTest extends ListenerTestCase
{
    protected $role;

    protected function setUp(): void
    {
        parent::setUp();
        $this->role = factory(\Kouloughli\Role::class)->create();
    }

    /** @test */
    public function onCreate()
    {
        event(new Created($this->role));
        $this->assertMessageLogged("Created new role called {$this->role->display_name}.");
    }

    /** @test */
    public function onUpdate()
    {
        event(new Updated($this->role));
        $this->assertMessageLogged("Updated role with name {$this->role->display_name}.");
    }

    /** @test */
    public function onDelete()
    {
        event(new Deleted($this->role));
        $this->assertMessageLogged("Deleted role named {$this->role->display_name}.");
    }

    /** @test */
    public function onPermissionsUpdate()
    {
        event(new PermissionsUpdated());
        $this->assertMessageLogged("Updated role permissions.");
    }
}
