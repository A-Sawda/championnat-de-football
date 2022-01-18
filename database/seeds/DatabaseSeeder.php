<?php

use Illuminate\Database\Seeder;
use App\Repositories\Repository;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */

    /*php artisan db:seed Cette commande déclenche l'exécution de la méthode run de la classe DatabaseSeeder. 
    Cette classe est définie dans le fichier database/seeds/DatabaseSeeder.php */
    //php artisan db:seed
    public function run()
    {
        touch('database/database.sqlite');
        $this->repository = new Repository();
        $this->repository->createDatabase();
        $this->repository->insertTeam(['id'=>1, 'name' => 'Marseille']);
        $this->repository->insertTeam(['id'=>2, 'name' => 'Bordeaux']);
        $this->repository->insertTeam(['id'=>3, 'name' => 'Nantes']);
        $this->repository->insertTeam(['name' => 'Paris']);
        $this->repository->insertMatch(['id'=>1, 'team0'=>1, 'team1'=>2, 'score0'=>0, 'score1'=>1, 'date'=>'2022-01-01 10:00']);
        $this->repository->insertMatch(['team0'=>1, 'team1'=>3, 'score0'=>1, 'score1'=>1, 'date'=>'2022-01-03 10:00']);
        //SELECT * FROM teams;
        //var_dump($this->repository->team(3));
        //var_dump($this->repository->matches());
    }

}
