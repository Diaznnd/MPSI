<?php

namespace Tests\Unit;

use App\Models\Pendaftaran;
use App\Models\User;
use App\Models\Workshop;
use Illuminate\Container\Container;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Events\Dispatcher;
use Mockery;
use Tests\TestCase;

class PendaftaranTest extends TestCase
{
    protected function tearDown(): void
    {
        Mockery::close();
        Pendaftaran::unsetEventDispatcher();

        parent::tearDown();
    }

    public function test_table_primary_key_and_timestamps_configuration(): void
    {
        $model = new Pendaftaran();

        $this->assertSame('pendaftaran', $model->getTable());
        $this->assertSame('pendaftaran_id', $model->getKeyName());
        $this->assertFalse($model->timestamps);
        $this->assertSame(
            ['user_id', 'workshop_id', 'tanggal_daftar', 'status_pendaftaran'],
            $model->getFillable()
        );
    }

    public function test_user_relationship_definition(): void
    {
        $model = new Pendaftaran();

        $relation = $model->user();

        $this->assertInstanceOf(BelongsTo::class, $relation);
        $this->assertSame('user_id', $relation->getForeignKeyName());
        $this->assertSame('user_id', $relation->getOwnerKeyName());
        $this->assertInstanceOf(User::class, $relation->getRelated());
    }

    public function test_workshop_relationship_definition(): void
    {
        $model = new Pendaftaran();

        $relation = $model->workshop();

        $this->assertInstanceOf(BelongsTo::class, $relation);
        $this->assertSame('workshop_id', $relation->getForeignKeyName());
        $this->assertSame('workshop_id', $relation->getOwnerKeyName());
        $this->assertInstanceOf(Workshop::class, $relation->getRelated());
    }

    public function test_created_event_triggers_auto_deactivate_if_quota_full(): void
    {
        Pendaftaran::setEventDispatcher(new Dispatcher(new Container()));

        $workshop = Mockery::mock(Workshop::class);
        $workshop->shouldReceive('autoDeactivateIfQuotaFull')
            ->once();

        $model = new Pendaftaran();
        $model->setRelation('workshop', $workshop);

        $ref = new \ReflectionClass(Pendaftaran::class);
        $method = $ref->getMethod('fireModelEvent');
        $method->setAccessible(true);

        $method->invoke($model, 'created', false);

        // If the event listener is not called as expected, Mockery will throw.
        // We explicitly bump the assertion count so PHPUnit does not treat this
        // behavior-verification test as risky.
        $this->addToAssertionCount(1);
    }
}
