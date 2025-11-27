<?php

namespace Tests\Unit;

use App\Models\Absensi;
use App\Models\ForumDiskusi;
use App\Models\Pendaftaran;
use App\Models\User;
use App\Models\Workshop;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Tests\TestCase;

class UserTest extends TestCase
{
    public function test_has_role_returns_true_for_matching_role(): void
    {
        $user = new User(['role' => 'admin']);

        $this->assertTrue($user->hasRole('admin'));
    }

    public function test_has_role_returns_false_for_different_role(): void
    {
        $user = new User(['role' => 'pengguna']);

        $this->assertFalse($user->hasRole('admin'));
    }

    public function test_is_pemateri_returns_true_when_role_is_pemateri(): void
    {
        $user = new User(['role' => 'pemateri']);

        $this->assertTrue($user->isPemateri());
    }

    public function test_is_pemateri_returns_false_when_role_is_not_pemateri(): void
    {
        $user = new User(['role' => 'pengguna']);

        $this->assertFalse($user->isPemateri());
    }

    public function test_timestamps_disabled(): void
    {
        $user = new User();

        $this->assertFalse($user->timestamps);
    }

    public function test_workshops_relationship_definition(): void
    {
        $user = new User();

        $relation = $user->workshops();

        $this->assertInstanceOf(HasMany::class, $relation);
        $this->assertSame('pemateri_id', $relation->getForeignKeyName());
        $this->assertSame('user_id', $relation->getLocalKeyName());
        $this->assertInstanceOf(Workshop::class, $relation->getRelated());
    }

    public function test_pendaftarans_relationship_definition(): void
    {
        $user = new User();

        $relation = $user->pendaftarans();

        $this->assertInstanceOf(HasMany::class, $relation);
        $this->assertSame('user_id', $relation->getForeignKeyName());
        $this->assertSame('user_id', $relation->getLocalKeyName());
        $this->assertInstanceOf(Pendaftaran::class, $relation->getRelated());
    }

    public function test_forum_diskusi_relationship_definition(): void
    {
        $user = new User();

        $relation = $user->forumDiskusi();

        $this->assertInstanceOf(HasMany::class, $relation);
        $this->assertSame('user_id', $relation->getForeignKeyName());
        $this->assertSame('user_id', $relation->getLocalKeyName());
        $this->assertInstanceOf(ForumDiskusi::class, $relation->getRelated());
    }

    public function test_absensi_relationship_definition(): void
    {
        $user = new User();

        $relation = $user->absensi();

        $this->assertInstanceOf(HasMany::class, $relation);
        $this->assertSame('user_id', $relation->getForeignKeyName());
        $this->assertSame('user_id', $relation->getLocalKeyName());
        $this->assertInstanceOf(Absensi::class, $relation->getRelated());
    }
}
