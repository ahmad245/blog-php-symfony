<?php

namespace App\Entity;

// use Doctrine\ORM\Mapping as ORM;

// /**
//  * @ORM\Entity(repositoryClass="App\Repository\BlogRepository")
//  */
// class Blog
// {
//     /**
//      * @ORM\Id()
//      * @ORM\GeneratedValue()
//      * @ORM\Column(type="integer")
//      */
//     private $id;

//     public function getId(): ?int
//     {
//         return $this->id;
//     }
// }

// <?php

// namespace App\DataFixtures;

// use App\Entity\BlogType;
// use App\Entity\Post;
// use App\Entity\User;
// use App\Entity\Comment;
// use App\Security\TokenGenerator;
// use Doctrine\Bundle\FixturesBundle\Fixture;
// use Doctrine\Common\Persistence\ObjectManager;
// use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

// // "title": "A new blog post!",
// //     "published": "2018-07-01 12:00:00",
// //     "content": "Hello there!",
// //     "author": "Piotr Jura",
// //     "slug": "a-new-blog-post"
// class AppFixtures extends Fixture
// {
//     private $passwordEncoder;

//     /**
//      * @var \Faker\Factory
//      */
//     private $faker;

//     private const USERS = [
//         [
//              'firstName'=>'superAdmin',
//             'lastName'=>'almasri1',
//             'email' => 'admin@blog.com',
//             'password' => 'secret123#',
//             'roles'=>[User::ROLE_SUPERADMIN],
//             'enabled' => true

//         ],
//         [
//             'firstName'=>'writer',
//             'lastName'=>'almasri2',
//             'email' => 'john@blog.com',
//             'password' => 'secret123#',
//             'roles'=>[User::ROLE_WRITER],
//             'enabled' => true


//         ],
//         [
//             'firstName'=>'commentor',
//             'lastName'=>'almasri3',
//             'email' => 'rob@blog.com',
//             'password' => 'secret123#',
//             'roles'=>[User::ROLE_COMMENTATOR],
//             'enabled' => true

//         ],
//         [
//             'firstName'=>'admin',
//             'lastName'=>'almasri4',
//             'email' => 'jenny@blog.com',
//             'password' => 'secret123#',
//             'roles'=>[User::ROLE_ADMIN],
//             'enabled' => true

//         ],
//         [
//             'firstName'=>'editor',
//             'lastName'=>'almasri5',
//             'email' => 'han@blog.com',
//             'password' => 'secret123#',
//             'roles'=>[User::ROLE_EDITOR],
//             'enabled' => false

//         ],
//         [
//             'firstName'=>'defalut',
//             'lastName'=>'almasri6',
//             'email' => 'jedi@blog.com',
//             'password' => 'secret123#',
//             'roles'=>User::DEFAULT_ROLES,
//             'enabled' => true

//         ],
//     ];
//     private $tokenGenerator;
//     public function __construct(UserPasswordEncoderInterface $passwordEncoder,TokenGenerator $tokenGenerator)
//     {
//         $this->passwordEncoder=$passwordEncoder;
//         $this->faker = \Faker\Factory::create();
//         $this->tokenGenerator=$tokenGenerator;
//     }
//     /**
//      * Load data fixtures with the passed EntityManager
//      * @param ObjectManager $manager
//      */
//     public function load(ObjectManager $manager)
//     {
//         // $product = new Product();
//         // $manager->persist($product);
//          $this->loadUser($manager);
//         $this->loadBlogType($manager);
        
//         $this->loadPost($manager);
//         $this->loadComments($manager);
       

//     }
//     public function loadBlogType(ObjectManager $manager){
//         for ($i = 0; $i < 10; $i++) {

//             $blogType = new BlogType();
//             $blogType->setName($this->faker->realText(30));
//             $this->setReference("blogType_$i", $blogType);
//             $manager->persist($blogType);
//         }
//     $manager->flush();

//     }
//     public function loadPost(ObjectManager $manager){
//         for ($i = 0; $i < 10; $i++) {

//             $post = new Post();
//             $post->setTitle($this->faker->realText(30));
//             $post->setContent($this->faker->realText());
//             $post->setPublish($this->faker->boolean);
//             $post->setSlug($this->faker->slug);
//             $post->setDate($this->faker->dateTime);
//             $blogTypeReference=$this->getReference("blogType_$i");
//             $post->setBlogType($blogTypeReference);
//             $authorReference = $this->getRandomUserReference($post);
            
//             $post->setAuthor($authorReference);
          
//             $this->setReference("post_$i", $post);
            
//             $manager->persist($post);
//         }
//     $manager->flush();

//     }

   

//     public function loadUser(ObjectManager $manager){
//         foreach (self::USERS as $userFixture) {
//             $user = new User();
//             $user->setFirstName($userFixture['firstName']);
//             $user->setLastName($userFixture['lastName']);
//             $user->setEmail($userFixture['email']);
//             $user->setEnabled($userFixture['enabled']);
//             $user->setRoles($userFixture['roles']);
          
//             $user->setPassword($this->passwordEncoder->encodePassword($user, $userFixture['password']));
                 
//             $this->addReference('user_'.$userFixture['firstName'], $user);
//             if(!$userFixture['enabled']){
//                 $user->setConfirmationToken($this->tokenGenerator->getRandomSecureToken());
//               }

//             $manager->persist($user);
//         }
//         $manager->flush();
       


//     }
//     public function loadComments(ObjectManager $manager)
//     {
//         for ($i = 0; $i < 10; $i++) {
//             for ($j = 0; $j < rand(1, 10); $j++) {
//                 $comment = new Comment();
//                 $comment->setMessage($this->faker->realText());
//                 $comment->setDate($this->faker->dateTimeThisYear);

//                 $authorReference = $this->getRandomUserReference($comment);

//                 $comment->setAuthor($authorReference);
//                 $comment->setPost($this->getReference("post_$i"));

//                 $manager->persist($comment);
//             }
//         }

//         $manager->flush();
//     }
//     public function getRandomUserReference($entity)
//     {
//         $randomUser = self::USERS[rand(0, 5)];
         
        
//         // if ($entity instanceof Post && !count(array_intersect(
//         //     $randomUser['roles'],

//         //     [User::ROLE_SUPERADMIN, User::ROLE_ADMIN, User::ROLE_WRITER]
//         // ))) {
               
//         //     return $this->getRandomUserReference($entity);
//         // }

//         // if ($entity instanceof Comment && !count(
//         //         array_intersect(
//         //             $randomUser['roles'],
//         //             [
//         //                 User::ROLE_SUPERADMIN,
//         //                 User::ROLE_ADMIN,
//         //                 User::ROLE_WRITER,
//         //                 User::ROLE_COMMENTATOR,
//         //             ]
//         //         )
//         //     )) {
//         //     return $this->getRandomUserReference($entity);
//         // }


//         return $this->getReference(
//             'user_'.$randomUser['firstName']
//         );
//     }


// }
