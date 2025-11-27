<?php

namespace Tests\Unit;

use App\Models\Keyword;
use App\Models\Workshop;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Tests\TestCase;

class KeywordTest extends TestCase
{
    public function test_table_timestamps_and_fillable_configuration(): void
    {
        $model = new Keyword();

        $this->assertSame('keywords', $model->getTable());
        $this->assertFalse($model->timestamps);
        $this->assertSame(['keyword'], $model->getFillable());
    }

    public function test_workshops_relationship_definition(): void
    {
        $model = new Keyword();

        $relation = $model->workshops();

        $this->assertInstanceOf(BelongsToMany::class, $relation);
        $this->assertSame('workshop_keyword', $relation->getTable());
        $this->assertSame('keyword_id', $relation->getForeignPivotKeyName());
        $this->assertSame('workshop_id', $relation->getRelatedPivotKeyName());
        $this->assertInstanceOf(Workshop::class, $relation->getRelated());
    }
}
