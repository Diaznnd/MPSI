<?php

namespace Tests\Unit;

use App\Models\RequestWorkshop;
use App\Models\User;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Tests\TestCase;

class RequestWorkshopTest extends TestCase
{
    public function test_table_primary_key_timestamps_and_fillable_configuration(): void
    {
        $model = new RequestWorkshop();

        $this->assertSame('request_workshop', $model->getTable());
        $this->assertSame('request_id', $model->getKeyName());
        $this->assertFalse($model->timestamps);
        $this->assertSame(
            ['user_id', 'judul', 'deskripsi', 'status_request', 'tanggal_tanggapan', 'catatan_admin'],
            $model->getFillable()
        );
    }

    public function test_user_relationship_definition(): void
    {
        $model = new RequestWorkshop();

        $relation = $model->user();

        $this->assertInstanceOf(BelongsTo::class, $relation);
        $this->assertSame('user_id', $relation->getForeignKeyName());
        $this->assertSame('user_id', $relation->getOwnerKeyName());
        $this->assertInstanceOf(User::class, $relation->getRelated());
    }
}
