<?php

namespace Tests\Unit;

use App\Models\MateriWorkshop;
use App\Models\Workshop;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Tests\TestCase;

class MateriWorkshopTest extends TestCase
{
    public function test_table_primary_key_timestamps_and_fillable_configuration(): void
    {
        $model = new MateriWorkshop();

        $this->assertSame('materi_workshop', $model->getTable());
        $this->assertSame('materi_id', $model->getKeyName());
        $this->assertFalse($model->timestamps);
        $this->assertSame(
            ['workshop_id', 'judul_topik', 'file_materi_url'],
            $model->getFillable()
        );
    }

    public function test_workshop_relationship_definition(): void
    {
        $model = new MateriWorkshop();

        $relation = $model->workshop();

        $this->assertInstanceOf(BelongsTo::class, $relation);
        $this->assertSame('workshop_id', $relation->getForeignKeyName());
        $this->assertSame('workshop_id', $relation->getOwnerKeyName());
        $this->assertInstanceOf(Workshop::class, $relation->getRelated());
    }
}
