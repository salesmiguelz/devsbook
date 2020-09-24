<?php
namespace src\controllers;

use \src\models\User;
use \core\Controller;
use \src\handlers\UserHandler;
use \src\handlers\PostHandler;

class ConfigController extends Controller {

    private $loggedUser;

    public function __construct(){
        $this->loggedUser = UserHandler::checkLogin();
        if( $this->loggedUser === false){
            $this->redirect('/login');
        }
        
    }
    
    public function index(){
        $user = UserHandler::getUser($this->loggedUser->id);
        $flash = '';
        if(!empty($_SESSION['flash'])){
            $flash = $_SESSION['flash'];
            $_SESSION['flash'] = '';
        }
        $this->render('config', [
            'loggedUser' => $this->loggedUser,
            'user' => $user,
            'flash' => $flash
        ]);
    }

    public function configAction(){

        
        $avatarName = '';
        $coverName = '';
        $name = filter_input(INPUT_POST, 'name');
        $birthdate = filter_input(INPUT_POST, 'birthdate');
        $email = filter_input(INPUT_POST, 'email',  FILTER_SANITIZE_EMAIL);
        $city = filter_input(INPUT_POST, 'city');
        $work = filter_input(INPUT_POST, 'work');
        $password = filter_input(INPUT_POST, 'password');
        $confirmedPassword = filter_input(INPUT_POST, 'confirmedPassword');

        $hash = password_hash($password, PASSWORD_DEFAULT);



       if ($name || $birthdate || $email || $city || $work || $password || $confirmedPassword){ 
            $birthdate = explode('/', $birthdate);

            if(count($birthdate) != 3){
                $_SESSION['flash'] = 'Data de nascimento inválida!';
                $this->redirect('/config');
            }
            
            $birthdate = $birthdate[2].'-'.$birthdate[1].'-'.$birthdate[0]; //Convertendo pra YYYY-MM-DD

            if(strtotime($birthdate) === false){//Verifica se é uma data real
                $_SESSION['flash'] = 'Data de nascimento não existe!';
                $this->redirect('/config');
                
            }

            if(UserHandler::isMineEmail($email, $this->loggedUser->id) === false){
                if(UserHandler::emailExists($email)){
                    $_SESSION['flash'] = 'E-mail já existe!';
                    $this->redirect('/config');
                }
            }
            
             //Avatar
             if(isset($_FILES['avatar']) && !empty($_FILES['avatar']['tmp_name'])) {
                $newAvatar = $_FILES['avatar'];
                if(in_array($newAvatar['type'], ['image/jpeg', 'image/jpg', 'image/png'])) {
                    $avatarName = $this->cutImage($newAvatar, 200, 200, 'media/avatars');
                   


                }
            }
            
            //Cover
            if(isset($_FILES['cover']) && !empty($_FILES['cover']['tmp_name'])) {
                $newCover = $_FILES['cover'];

                if(in_array($newCover['type'], ['image/jpeg', 'image/jpg', 'image/png'])) {
                    $coverName = $this->cutImage($newCover, 850, 310, 'media/covers')
                    ;
                   
                }
            }

            //Senha
            if($password != $confirmedPassword){
                $_SESSION['flash'] = 'As senhas não são iguais!';
                $this->redirect('/config');
            }



            User::update()
                ->set('avatar', $avatarName)
                ->set('cover', $coverName)
                ->set('name', $name)
                ->set('birthdate', $birthdate)
                ->set('email', $email)
                ->set('city', $city)
                ->set('work', $work)
                ->set('password', $hash)
            ->where('id', $this->loggedUser->id)
            ->execute();

            

       
        } else{
            $_SESSION['flash'] = 'Preencha os dados corretamente!';
            $this->redirect('/config');
        } 

        
        $this->redirect('/perfil');
       
       

    }

    private function cutImage($file, $w, $h, $folder) {
        list($widthOrig, $heightOrig) = getimagesize($file['tmp_name']);
        $ratio = $widthOrig / $heightOrig;

        $newWidth = $w;
        $newHeight = $newWidth / $ratio;

        if($newHeight < $h) {
            $newHeight = $h;
            $newWidth = $newHeight * $ratio;
        }

        $x = $w - $newWidth;
        $y = $h - $newHeight;
        $x = $x < 0 ? $x / 2 : $x;
        $y = $y < 0 ? $y / 2 : $y;

        $finalImage = imagecreatetruecolor($w, $h);
        switch($file['type']) {
            case 'image/jpeg':
            case 'image/jpg':
                $image = imagecreatefromjpeg($file['tmp_name']);
            break;
            case 'image/png':
                $image = imagecreatefrompng($file['tmp_name']);
            break;
        }

        imagecopyresampled(
            $finalImage, $image,
            $x, $y, 0, 0,
            $newWidth, $newHeight, $widthOrig, $heightOrig
        );

        $fileName = md5(time().rand(0,9999)).'.jpg';

        imagejpeg($finalImage, $folder.'/'.$fileName);

        return $fileName;
    }
}