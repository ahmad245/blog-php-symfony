<?php

namespace App\DataFixtures;

use App\Entity\Post;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

// "title": "A new blog post!",
//     "published": "2018-07-01 12:00:00",
//     "content": "Hello there!",
//     "author": "Piotr Jura",
//     "slug": "a-new-blog-post"
class AppFixtures extends Fixture
{
    private $passwordEncoder;
    public function __construct(UserPasswordEncoderInterface $passwordEncoder)
    {
        $this->passwordEncoder=$passwordEncoder;
    }
    public function load(ObjectManager $manager)
    {
        // $product = new Product();
        // $manager->persist($product);
        $this->loadUser($manager);
       $this->loadPost($manager);
       
    }
    public function loadPost(ObjectManager $manager){
    $user=$this->getReference('user_admin');
    $post=new Post();
    $post->setTitle('first post for online');
    $post->setContent('symfony');
    $post->setPublish(true);
    $post->setSlug('first post ');
    $post->setDate(new \DateTime('2018-07-01 12:00:00'));
    $post->setAuthor($user);

    $manager->persist($post);
    $manager->flush();
    /////////////
    $post=new Post();
    $post->setTitle('second post for online');
    $post->setContent('symfony2');
    $post->setPublish(true);
    $post->setSlug('second post');
    $post->setDate(new \DateTime('2018-07-01 12:00:00'));
    $post->setAuthor($user);

    $manager->persist($post);
    $manager->flush();
    }

    public function loadUser(ObjectManager $manager){
        $user=new User();
        $user->setFirstName('ahmad');
        $user->setLastName('almasri');
        $user->setEmail('ahmad@gmail.com');
        $user->setPassword($this->passwordEncoder->encodePassword($user,'123456'));
        
        $this->addReference('user_admin',$user);

        $manager->persist($user);
        $manager->flush();


    }
}
