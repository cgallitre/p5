<?php

namespace App\DataFixtures;

use Faker\Factory;
use App\Entity\User;
use App\Entity\Message;
use App\Entity\Training;
use App\Entity\Portfolio;
use App\Entity\Testimonial;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class AppFixtures extends Fixture
{
    private $encoder;

    public function __construct(UserPasswordEncoderInterface $encoder)
    {
        $this->encoder = $encoder;
    }

    public function load(ObjectManager $manager)
    {
        $faker = Factory::create('fr-FR');

        // Utilisateurs

        $users= [];

        for ($i=1; $i<=5; $i++){
            
            $user = new User;

            $hash = $this->encoder->encodePassword($user, 'password');

            $user
                ->setFirstName($faker->firstname)
                ->setLastName($faker->lastname)
                ->setEmail($faker->email)
                ->setProject('<p>' . join('</p><p>', $faker->paragraphs(4)) . '</p>')
                ->setHash($hash)
                ->setCompany($faker->sentence())
                ->setStatus(mt_rand(0,1))
                ;
            $manager->persist($user);
            $users[] = $user;
        }

        // Messages
        for ($i=1; $i<20; $i++){
            $message = new Message;

            $user = $users[mt_rand(0, count($users)-1)];
            $message
                ->setTitle($faker->sentence())
                ->setContent('<p>' . join('</p><p>', $faker->paragraphs(3)) . '</p>')
                ->setCreatedAt($faker->dateTimeBetween($startDate = '-1 year', $endDate = 'now', $timezone = 'Europe/Paris'))
                ->setAuthor($user)
                ;
            $manager->persist($message);
        }

        // Formations
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

        // Testimonial

        for ($i=1; $i<=3; $i++){

            $testimonial = new Testimonial;
            $testimonial
                ->setContent($faker->paragraph(3))
                ->setAuthor($faker->name())
                ->setPublished(1)
                ;
            $manager->persist($testimonial);
        }

        // Portfolio

        for ($i=1; $i<=6; $i++){

            $portfolio = new Portfolio;
            $portfolio
                ->setTitle($faker->text($maxNbChars = 30))
                ->setDescription($faker->paragraph(2))
                ->setTechnology($faker->text($maxNbChars = 30))
                ->setUrl($faker->url())
                ->setCoverImage('http://placehold.it/150x100')
                ;
                $manager->persist($portfolio);
        }

        // Save in database

        $manager->flush();
    }
}
