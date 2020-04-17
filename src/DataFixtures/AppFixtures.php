<?php

namespace App\DataFixtures;

use Faker\Factory;
use App\Entity\Training;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $faker = Factory::create('fr-FR');

        for ($i =1; $i<=6; $i++){
            $training = new Training;

            $title = $faker->sentence(4);
            $excerpt = $faker->paragraph(2);
            $duration = mt_rand(1,5);
            $objectives = $faker->sentence();
            $level = $faker->sentence();
            $public = $faker->sentence();
            $program = '<p>' . join('</p><p>', $faker->paragraphs(6)) . '</p>';

            $training
                ->setTitle($title)
                ->setExcerpt($excerpt)
                ->setDuration($duration)
                ->setObjectives($objectives)
                ->setLevel($level)
                ->setPublic($public)
                ->setProgram($program);

            $manager->persist($training);
        }

        $manager->flush();
    }
}
