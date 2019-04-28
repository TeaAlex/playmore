<?php


namespace App\Util;


class User {
    private $nom;
    private $prenom;
    private $email;
    private $age;
    public function __construct($n,$p,$e,$a) {
        $this->nom = $n;
        $this->prenom = $p;
        $this->email = $e;
        $this->age = $a;
    }
    public function isValid(){
        $val = 0;
        if(!empty($this->email)
            && filter_var($this->email, FILTER_VALIDATE_EMAIL)
            && !empty($this->nom)
            && !empty($this->prenom)
            && !empty($this->age) && $this->age > 13)
        {
            return true;
        }
        return false;
    }

    /**
     * @return mixed
     */
    public function getAge()
    {
        return $this->age;
    }

    /**
     * @param mixed $age
     */
    public function setAge($age): void
    {
        $this->age = $age;
    }

}

