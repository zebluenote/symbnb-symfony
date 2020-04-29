<?php

namespace App\DataFixtures;

use App\Entity\Ad;
use Faker\Factory;
use App\Entity\Role;
use App\Entity\User;
use App\Entity\Image;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class AppFixtures extends Fixture
{

    public function __construct(UserPasswordEncoderInterface $encoder)
    {
        $this->encoder = $encoder;
    }

    public function load(ObjectManager $manager)
    {
        $faker = Factory::create('fr_FR');

        $adminRole = new Role();
        $adminRole->setTitle('ROLE_ADMIN');
        $manager->persist($adminRole);

        // Création d'un utilistateur Admin
        $adminUser = new User();
        $adminUser
            ->setFirstName('Arnaud')
            ->setLastName('FROUIN')
            ->setEmail('arnaud.frouin@wanadoo.fr')
            ->setHash($this->encoder->encodePassword($adminUser, 'carolles'))
            ->setPicture('https://www.gravatar.com/avatar/da29808b3056028b0294e2ce47404dd3')
            ->setIntroduction($faker->sentence())
            ->setDescription('<p>' . join('</p><p>', $faker->paragraphs(2)) . '</p>')
            ->addUserRole($adminRole);
        $manager->persist($adminUser);

        // Gestion des utilisateurs
        $users = [];
        $genders = ['male', 'female'];

        for ($i = 1; $i <= 10; $i++) {

            $user = new User();
            $gender = $faker->randomElement($genders);
            $ruGender = ($gender == 'male' ? 'men' : 'women');
            $pictureId = $faker->numberBetween(1, 99);
            $picture = "https://randomuser.me/api/portraits/{$ruGender}/{$pictureId}.jpg";
            $hash = $this->encoder->encodePassword($user, 'password');

            $user->setFirstName($faker->firstName($gender))
                ->setLastName($faker->lastName)
                ->setEmail($faker->email)
                ->setIntroduction($faker->sentence())
                ->setDescription('<p>' . join('</p><p>', $faker->paragraphs(3)) . '</p>')
                ->setPicture($picture)
                ->setHash($hash);

            $manager->persist($user);

            $users[] = $user;
        }


        // Gestion des annonces
        for ($i = 1; $i <= 30; $i++) {

            $title = $faker->sentence(); // default=6
            $coverImage = "https://picsum.photos/1000/300";
            $introduction = $faker->paragraph(2);
            $content = '<p>' . join('</p><p>', $faker->paragraphs(5)) . '</p>';
            $author = $users[mt_rand(0, count($users) - 1)];

            $ad = new Ad();
            $ad->setTitle($title)
                ->setCoverImage($coverImage)
                ->setIntroduction($introduction)
                ->setContent($content)
                ->setPrice(mt_rand(40, 200))
                ->setRooms(mt_rand(1, 5))
                ->setAuthor($author);

            for ($j = 1; $j <= mt_rand(2, 5); $j++) {
                $image = new Image();
                $image->setAd($ad)
                    ->setCaption($faker->sentence(4))
                    ->setUrl('https://picsum.photos/1000/300');

                $manager->persist($image);
            }

            $manager->persist($ad);
        }

        $manager->flush();
    }
}
