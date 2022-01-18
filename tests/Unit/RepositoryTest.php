<?php

namespace Tests\Unit;

use PDO;
use Exception;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;
use App\Repositories\Data;
use App\Repositories\Ranking;
use App\Repositories\Repository;

class RepositoryTest extends TestCase
{
    public function setUp(): void
    {
        parent::setUp();
        $this->ranking = new Ranking();
        $this->data = new Data();
        $this->repository = new Repository();
        $this->repository->createDatabase();
    }
    
    function testTeamsAndInsertTeam(): void
    {
        $teams = $this->data->teams();
        $this->assertEquals($this->repository->insertTeam($teams[4]), 5);
        $this->assertEquals($this->repository->insertTeam($teams[2]), 3);
        $this->assertEquals($this->repository->insertTeam($teams[7]), 8);
        $this->assertEquals($this->repository->teams(), [$teams[2], $teams[4], $teams[7]]);
    }

    function testMatchesAndInsertMatch(): void
    {
        $teams = $this->data->teams();
        $matches = $this->data->matches();
        $this->assertEquals($this->repository->insertTeam($teams[6]), 7);
        $this->assertEquals($this->repository->insertTeam($teams[18]), 19);
        $this->assertEquals($this->repository->insertTeam($teams[5]), 6);
        $this->assertEquals($this->repository->insertTeam($teams[10]), 11);
        $this->assertEquals($this->repository->insertMatch($matches[5]), 6);
        $this->assertEquals($this->repository->insertMatch($matches[0]), 1);
        $this->assertEquals($this->repository->matches(), [$matches[0], $matches[5]]);
    }

    function testfillDatabase(): void
    {
        $this->repository->fillDatabase();
        $teams = $this->data->teams();
        $matches = $this->data->matches();
        $this->assertEquals($this->repository->teams(), $teams);
        $this->assertEquals($this->repository->matches(), $matches);
    }

    function testTeam(): void
    {
        $this->repository->fillDatabase();
        foreach ($this->data->teams() as $team) {
            $this->assertEquals($this->repository->team($team['id']), $team);
        }
    }
}