<?php

namespace Tests\Unit;

use App\Models\Absensi;
use App\Models\User;
use App\Models\Workshop;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Tests\TestCase;

class AbsensiTest extends TestCase
{
    public function test_table_primary_key_timestamps_and_fillable_configuration(): void
    {
        $model = new Absensi();

        $this->assertSame('absensi', $model->getTable());
        $this->assertSame('absensi_id', $model->getKeyName());
        $this->assertSame(
            ['user_id', 'workshop_id', 'waktu_absensi', 'status_absensi'],
            $model->getFillable()
        );
        $this->assertSame([
            'absensi_id' => 'int',
            'waktu_absensi' => 'datetime',
        ], $model->getCasts());
    }

    public function test_user_relationship_definition(): void
    {
        $model = new Absensi();

        $relation = $model->user();

        $this->assertInstanceOf(BelongsTo::class, $relation);
        $this->assertSame('user_id', $relation->getForeignKeyName());
        $this->assertSame('user_id', $relation->getOwnerKeyName());
        $this->assertInstanceOf(User::class, $relation->getRelated());
    }

    public function test_workshop_relationship_definition(): void
    {
        $model = new Absensi();

        $relation = $model->workshop();

        $this->assertInstanceOf(BelongsTo::class, $relation);
        $this->assertSame('workshop_id', $relation->getForeignKeyName());
        $this->assertSame('workshop_id', $relation->getOwnerKeyName());
        $this->assertInstanceOf(Workshop::class, $relation->getRelated());
    }
}
