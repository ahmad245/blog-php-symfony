<?php
namespace App\Controller;
// https://symfonycasts.com/screencast/doctrine-relations/many-to-one-relation
// DATABASE_URL=mysql://db_user:db_password@127.0.0.1:3306/db_name?serverVersion=5.7

use App\Entity\Post;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
/**
 * @Route("/post")
 */

class BlogController extends AbstractController{
   private const posts=[
   [ "id"=>1,"slug"=>"hello world1","title"=>"HELLOW WORLD1"],
   [ "id"=>2,"slug"=>"hello world2","title"=>"HELLOW WORLD2"],
   [ "id"=>3,"slug"=>"hello world3","title"=>"HELLOW WORLD3"],
    ];
  
    /**
     * @Route("/{page}",name="blog_list",requirements={"page"="\d+"},methods={"GET"})
     */
    public function list($page=1,Request $req){
        $repository=$this->getDoctrine()->getRepository(Post::class);
        $posts=$repository->findAll();
        $limit=$req->get('limit',10);
      return $this->json(
          ['page'=>$page,
          "limit"=>$limit,
           'end point'=>array_map(function(Post $item){
            return  $this->generateUrl('blog_by_slug',["slug"=>$item->getSlug()]);
           },$posts),
           'data'=>$posts
          ]
          );
    }

    /**
     * @Route("/blog/{id}", name="blog_by_id"  , requirements={"id"="\d+"} , methods={"GET"})
     */
    public function post($id){
       $repository=$this->getDoctrine()->getRepository(Post::class);
       $post=$repository->find($id);
         return $this->json(
            $post
            );
           
    }
    // public function post(Post $post){
     
    //          return $this->json($post);
    //  }
    
    /**
     * @Route("/blog/{slug}",name="blog_by_slug" , methods={"GET"})
     */
    public function postBySlug($slug){
       return $this->json(
         $this->getDoctrine()->getRepository(Post::class)->findOneBy(["slug"=>$slug])
        );
    }

    /**
     * @Route("/add",name="blog_add",methods={"POST"} )
     */
    public function add(Request $req){
        /**@var Serializer $serializer */
        $serializer=$this->get('serializer');
        $post=$serializer->deserialize($req->getContent(),Post::class,'json');
        $em=$this->getDoctrine()->getManager();
        $em->persist($post);
        $em->flush();
        return    $this->json($post);
    }
    
    /**
     * @Route("/delete/{id}",name="blog_delete",methods={"DELETE"})
     */
    public function delete(Post $post){
       // $post=$this->getDoctrine()->getRepository(Post::class)->find($id);
        $em=$this->getDoctrine()->getManager();
        $em->remove($post);
        $em->flush();
        return $this->json(null);
    }
        
    }
