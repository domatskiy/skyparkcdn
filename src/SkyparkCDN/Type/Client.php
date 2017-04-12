<?php

namespace Domatskiy\SkyparkCDN\Type;

class Client extends Type
{
    public $id;
    public $email;
    public $name;
    public $ready;
    public $traffic;
    public $currentUser;
    public $mainUser;
    public $lang;
    public $balance;
    public $totpEnabled;
    public $phone = [];
    public $news = [];
    public $unfinishedGuideSteps;
    public $role;
    public $userRole;
    public $timeZoneOffset;
}