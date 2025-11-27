<?php

namespace Tests\Unit;

use App\Models\ForumDiskusi;
use App\Models\User;
use App\Models\Workshop;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Tests\TestCase;

class ForumDiskusiTest extends TestCase
{
    public function test_table_primary_key_timestamps_and_fillable_configuration(): void
    {
        $model = new ForumDiskusi();

        $this->assertSame('forum_diskusi', $model->getTable());
        $this->assertSame('discussion_id', $model->getKeyName());
        $this->assertTrue($model->timestamps);
        $this->assertSame(
            ['workshop_id', 'user_id', 'message'],
            $model->getFillable()
        );
        $dates = $model->getDates();
        $this->assertContains('created_at', $dates);
        $this->assertContains('updated_at', $dates);
    }

    public function test_workshop_relationship_definition(): void
    {
        $model = new ForumDiskusi();

        $relation = $model->workshop();

        $this->assertInstanceOf(BelongsTo::class, $relation);
        $this->assertSame('workshop_id', $relation->getForeignKeyName());
        $this->assertSame('workshop_id', $relation->getOwnerKeyName());
        $this->assertInstanceOf(Workshop::class, $relation->getRelated());
    }

    public function test_user_relationship_definition(): void
    {
        $model = new ForumDiskusi();

        $relation = $model->user();

        $this->assertInstanceOf(BelongsTo::class, $relation);
        $this->assertSame('user_id', $relation->getForeignKeyName());
        $this->assertSame('user_id', $relation->getOwnerKeyName());
        $this->assertInstanceOf(User::class, $relation->getRelated());
    }
}
