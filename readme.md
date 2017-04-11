# skyparkcdn

## install 

```
$ composer require domatskiy/skyparkcdn
```
 
```
$cdn = new \Domatskiy\SkyparkCDN();
$response = $cdn->signin($email, $password);

if($response->isSuccess())
{
    # $token = $cdn->getToken();
    
    $rsBalance = $cdn
                ->client()
                ->getBalance();
 
    $rsCache = $cdn
                ->cache()
                ->purgeAll($res_id);
}
else
{
    $err = $response->getErrors();
}