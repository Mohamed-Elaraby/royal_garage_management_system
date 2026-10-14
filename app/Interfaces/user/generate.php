<?php


namespace App\Interfaces\user;


class generate implements makeRandomData
{

    public $username;
    public $password;


    public function randomPassword() {
        $alphabet = "abcdefghijklmnopqrstuwxyzABCDEFGHIJKLMNOPQRSTUWXYZ0123456789";
        $pass = array(); //remember to declare $pass as an array
        $alphaLength = strlen($alphabet) - 1; //put the length -1 in cache
        for ($i = 0; $i < 8; $i++) {
            $n = rand(0, $alphaLength);
            $pass[] = $alphabet[$n];
        }
        return $this->password = implode($pass);
    }

    public function username()
    {
        $username = rand(1000000, 100000000);
        return $username;
    }

    public function password()
    {
        $text_password = $this->randomPassword();
        $hashed_password = bcrypt($text_password);
        return ['text_password'=> $text_password, 'hashed_password' => $hashed_password];
    }
}
