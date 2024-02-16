<?php 

namespace ScreenJSON\Laravel\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Contracts\Foundation\Application;


use ScreenJSON\Interfaces\DecrypterInterface AS ScreenplayDecryptionContract;
use ScreenJSON\Interfaces\EncrypterInterface AS ScreenplayEncryptionContract;
use ScreenJSON\Interfaces\ScreenplayInterface AS ScreenplayContract;
use ScreenJSON\Interfaces\ValidatorInterface AS ScreenplayValidationContract;

use ScreenJSON\Decrypter;
use ScreenJSON\Encrypter;
use ScreenJSON\Screenplay;
use ScreenJSON\Validator;
 
class ScreenjsonSDKServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        $this->publishes ([
            __DIR__.'/../config/screenjson.php' => config_path ('screenjson.php'),
        ], 'screenjson');
    }

    public function register(): void
    {
        $this->app->bind (ScreenplayDecryptionContract::class, Decrypter::class);

        $this->app->bind (ScreenplayEncryptionContract::class, function (Application $app) 
        {
            return new Encrypter (config ('screenjson.screenplay.encryption'));
        });

        $this->app->bind (ScreenplayContract::class, function (Application $app) 
        {
            return new Screenplay (null, config ('screenjson.screenplay.defaults'));
        });
        
        $this->app->bind (ScreenplayValidationContract::class, Validator::class);
    }
}