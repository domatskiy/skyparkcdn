# skyparkcdn

## install 

```
$ composer require domatskiy/skyparkcdn
```

## use 
```
try{

    $CAuth = $this->cdn->signin($email, $password);
    
    #echo 'token = '.$CAuth->token."\n\n";

    $balance = $this->cdn
        ->client()
        ->getBalance();

    print_r($balance);

    $this->cdn
        ->cache()
        ->purgeAll($this->config['resource']['res_1']);

    
    $this->cdn->signout();
}
catch (\Exception $e)
{
    echo $e->getMessage();
}

```