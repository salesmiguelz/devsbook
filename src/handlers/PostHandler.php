<?php
namespace src\handlers;

use \src\models\Post;
use \src\models\User;
use \src\models\UserRelation;

class PostHandler {

    public static function addPost($idUser, $type, $body){
        $body = trim($body);

        if(!empty($idUser) && !empty($body) ){

            Post::insert([
                'id_user'=>$idUser,
                'type'=>$type,
                'created_at'=>date('Y-m-d H:i:s'),
                'body'=>$body
            ])->execute();
        }
    }

    public static function getHomeFeed($idUser, $page){

        $perPage = 2;
        //Pegar lista de usuários que eu sigo
        $userList = UserRelation::select()->where('user_from', $idUser)->get();
        $users = [];

        foreach($userList as $userItem){
            $users[] = $userItem['user_to'];
        }

        $users[] = $idUser;

        //Pegar os posts dessa galera ordenado pela data

        $postList = Post::select()
            ->where('id_user', 'in', $users)
            ->orderBy('created_at', 'desc')
            ->page($page, $perPage)
            ->get();
        //Numero total de posts q vão aparecer pra mim
        $total = Post::select()
            ->where('id_user', 'in', $users)
            ->count();

        $pageCount = ceil($total/$perPage); //Saber quantas páginas eu preciso

        //Transfomar o resultado em objetos dos models

        $posts = [];

        foreach($postList as $postItem){
            $newPost = new Post();
            $newPost->id = $postItem['id'];
            $newPost->type = $postItem['type'];
            $newPost->created_at = $postItem['created_at'];
            $newPost->body = $postItem['body'];
            $newPost->mine = false;


            if($postItem['id_user'] == $idUser){
                $newPost->mine = true;
            }

            //Preencher as informações adicionais de usuário no post

            $newUser = User::select()->where('id', $postItem['id_user'])->one();
            $newPost->user = new User();
            $newPost->user->id = $newUser['id'];
            $newPost->user->name = $newUser['name'];
            $newPost->user->avatar = $newUser['avatar'];

            //Likes
            $newPost->likeCount = 0;
            $newPost->liked = false;

            //Comentário

            $newPost->comments = [];
            $posts[] = $newPost;

            
        }
        return [
            'posts' => $posts,
            'pageCount' => $pageCount,
            'currentPage' => $page
        ];
    }

}