<?php

namespace Tests\Unit;

use App\Models\Absensi;
use App\Models\ForumDiskusi;
use App\Models\Keyword;
use App\Models\MateriWorkshop;
use App\Models\Pendaftaran;
use App\Models\User;
use App\Models\Workshop;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Tests\TestCase;

class WorkshopTest extends TestCase
{
    public function test_table_and_primary_key_configuration(): void
    {
        $workshop = new Workshop();

        $this->assertSame('workshops', $workshop->getTable());
        $this->assertSame('workshop_id', $workshop->getKeyName());
    }

    public function test_pemateri_relationship_definition(): void
    {
        $workshop = new Workshop();

        $relation = $workshop->pemateri();

        $this->assertInstanceOf(BelongsTo::class, $relation);
        $this->assertSame('pemateri_id', $relation->getForeignKeyName());
        $this->assertSame('user_id', $relation->getOwnerKeyName());
        $this->assertInstanceOf(User::class, $relation->getRelated());
    }

    public function test_keywords_relationship_definition(): void
    {
        $workshop = new Workshop();

        $relation = $workshop->keywords();

        $this->assertInstanceOf(BelongsToMany::class, $relation);
        $this->assertSame('workshop_keyword', $relation->getTable());
        $this->assertSame('workshop_id', $relation->getForeignPivotKeyName());
        $this->assertSame('keyword_id', $relation->getRelatedPivotKeyName());
        $this->assertInstanceOf(Keyword::class, $relation->getRelated());
    }

    public function test_pendaftaran_relationship_definition(): void
    {
        $workshop = new Workshop();

        $relation = $workshop->pendaftaran();

        $this->assertInstanceOf(HasMany::class, $relation);
        $this->assertSame('workshop_id', $relation->getForeignKeyName());
        $this->assertSame('workshop_id', $relation->getLocalKeyName());
        $this->assertInstanceOf(Pendaftaran::class, $relation->getRelated());
    }

    public function test_materi_relationship_definition(): void
    {
        $workshop = new Workshop();

        $relation = $workshop->materi();

        $this->assertInstanceOf(HasMany::class, $relation);
        $this->assertSame('workshop_id', $relation->getForeignKeyName());
        $this->assertSame('workshop_id', $relation->getLocalKeyName());
        $this->assertInstanceOf(MateriWorkshop::class, $relation->getRelated());
    }

    public function test_forum_diskusi_relationship_definition(): void
    {
        $workshop = new Workshop();

        $relation = $workshop->forumDiskusi();

        $this->assertInstanceOf(HasMany::class, $relation);
        $this->assertSame('workshop_id', $relation->getForeignKeyName());
        $this->assertSame('workshop_id', $relation->getLocalKeyName());
        $this->assertInstanceOf(ForumDiskusi::class, $relation->getRelated());
    }

    public function test_absensi_relationship_definition(): void
    {
        $workshop = new Workshop();

        $relation = $workshop->absensi();

        $this->assertInstanceOf(HasMany::class, $relation);
        $this->assertSame('workshop_id', $relation->getForeignKeyName());
        $this->assertSame('workshop_id', $relation->getLocalKeyName());
        $this->assertInstanceOf(Absensi::class, $relation->getRelated());
    }

    public function test_is_quota_full_uses_kuota_terisi_when_set(): void
    {
        $workshop = new Workshop([
            'kuota' => 10,
            'kuota_terisi' => 5,
        ]);

        $this->assertFalse($workshop->isQuotaFull());

        $workshop->kuota_terisi = 10;

        $this->assertTrue($workshop->isQuotaFull());
    }

    public function test_is_quota_full_returns_false_when_kuota_is_zero_or_null(): void
    {
        $workshop = new Workshop([
            'kuota' => null,
            'kuota_terisi' => 100,
        ]);

        $this->assertFalse($workshop->isQuotaFull());

        $workshop = new Workshop([
            'kuota' => 0,
            'kuota_terisi' => 100,
        ]);

        $this->assertFalse($workshop->isQuotaFull());
    }

    public function test_auto_deactivate_if_quota_full_changes_status_and_saves(): void
    {
        $workshop = new class(['kuota' => 5, 'kuota_terisi' => 5, 'status_workshop' => 'aktif']) extends Workshop {
            public bool $saved = false;

            public function save(array $options = []): bool
            {
                $this->saved = true;

                return true;
            }
        };

        $result = $workshop->autoDeactivateIfQuotaFull();

        $this->assertTrue($result);
        $this->assertSame('nonaktif', $workshop->status_workshop);
        $this->assertTrue($workshop->saved);
    }

    public function test_auto_deactivate_if_quota_full_does_nothing_when_not_full_or_not_active(): void
    {
        $workshop = new class(['kuota' => 10, 'kuota_terisi' => 5, 'status_workshop' => 'aktif']) extends Workshop {
            public bool $saved = false;

            public function save(array $options = []): bool
            {
                $this->saved = true;

                return true;
            }
        };

        $resultNotFull = $workshop->autoDeactivateIfQuotaFull();

        $this->assertFalse($resultNotFull);
        $this->assertSame('aktif', $workshop->status_workshop);
        $this->assertFalse($workshop->saved);

        $inactiveWorkshop = new class(['kuota' => 5, 'kuota_terisi' => 5, 'status_workshop' => 'nonaktif']) extends Workshop {
            public bool $saved = false;

            public function save(array $options = []): bool
            {
                $this->saved = true;

                return true;
            }
        };

        $resultInactive = $inactiveWorkshop->autoDeactivateIfQuotaFull();

        $this->assertFalse($resultInactive);
        $this->assertSame('nonaktif', $inactiveWorkshop->status_workshop);
        $this->assertFalse($inactiveWorkshop->saved);
    }
}
