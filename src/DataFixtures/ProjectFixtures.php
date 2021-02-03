<?php

namespace App\DataFixtures;

use DateTime;
use Faker;
use App\Entity\Project;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;

class ProjectFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {

        $faker = Faker\Factory::create('en_FR');
        for($i = 0; $i < 6; $i++){
        $project = new Project();
        $project->setAddDate(new DateTime('now'));
        $project->setTitle($faker->sentence());
        $project->setText($faker->text('500'));
        $manager->persist($project);
        }

        $manager->flush();
    }
}
