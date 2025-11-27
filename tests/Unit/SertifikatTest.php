<?php

namespace Tests\Unit;

use App\Models\Pendaftaran;
use App\Models\Sertifikat;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Tests\TestCase;

class SertifikatTest extends TestCase
{
    public function test_table_primary_key_timestamps_and_fillable_configuration(): void
    {
        $model = new Sertifikat();

        $this->assertSame('sertifikat', $model->getTable());
        $this->assertSame('sertifikat_id', $model->getKeyName());
        $this->assertFalse($model->timestamps);
        $this->assertSame(
            ['pendaftaran_id', 'file_url', 'tanggal_generate'],
            $model->getFillable()
        );
    }

    public function test_pendaftaran_relationship_definition(): void
    {
        $model = new Sertifikat();

        $relation = $model->pendaftaran();

        $this->assertInstanceOf(BelongsTo::class, $relation);
        $this->assertSame('pendaftaran_id', $relation->getForeignKeyName());
        $this->assertSame('pendaftaran_id', $relation->getOwnerKeyName());
        $this->assertInstanceOf(Pendaftaran::class, $relation->getRelated());
    }
}
