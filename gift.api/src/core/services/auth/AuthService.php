<?php


namespace gift\api\core\services\auth;

use Exception;
use gift\appli\core\services\auth\AuthInterface;
use gift\appli\core\domain\entities\User;
use \gift\appli\app\utils\CsrfService;

class AuthService implements AuthInterface
{

   protected string $email;

   protected bool $userConnected;
   protected int $role;



   public function login($values)
   {

      try {

         CsrfService::check($values['csrf']);

         $email = $values['email'];
         $password = $values['password'];

         if (!isset($email))
            throw new Exception('Email non renseignée');
         if (!isset($password))
            throw new Exception('Mdp non renseignée');


         $user = User::where('user_id', '=', $email)->first();


         if (password_verify($password, $user->password)) {


            $this->email = $user->user_id;
            $this->userConnected = true;
            $this->role = $user->role;
            $_SESSION['USER'] = $this;

         }




      } catch (Exception $e) {
         throw new Exception($e->getMessage());
      }



   }

   public function register(array $values)
   {



      try {

         CsrfService::check($values['csrf']);

         $email = $values['email'];
         $password1 = $values['password1'];
         $password2 = $values['password2'];


         if (isset($email) && isset($password1) && isset($password2)) {


            if ($password1 == $password2 && !$this->userAlreadyExist($email)) {

               $mdp_def = password_hash($password1, PASSWORD_DEFAULT, ['cost' => 12]);
               $email_def = filter_var($email, FILTER_SANITIZE_EMAIL);

               $new_user = new User();

               $new_user->user_id = $email_def;
               $new_user->password = $mdp_def;
               $new_user->role = 1;

               $new_user->save();

               $this->email = $email;
               $this->userConnected = true;
               $this->role = 1;

               $_SESSION['USER'] = $this;

            }
         }
      } catch (Exception $e) {
         throw new Exception($e->getMessage());
      }
   }


   public function userAlreadyExist(string $email): bool
   {

      $user = User::where('user_id', '=', $email);
      return (isset($user->user_id));
   }

   public function logout()
   {
      $_SESSION['USER'] = null;
   }

   public static function isAuthenticate()
   {
      return isset($_SESSION['USER']);
   }


}
