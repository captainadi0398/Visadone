<?php

namespace App\Http\Controllers;
use BotMan\BotMan\BotMan;
use Illuminate\Http\Request;
use BotMan\BotMan\Messages\Incoming\Answer;
use BotMan\BotMan\Messages\Outgoing\Actions\Button;



class BotManController extends Controller
{

    public function handle(Request $request)
    {
        $botman = app('botman');

        $botman->hears('start', function (BotMan $bot) {
            $bot->ask('What is your favorite color?', function (Answer $answer) use ($bot) {
                $color = $answer->getText();
                $bot->reply("Great! Your favorite color is $color.");
            });
        });

        $botman->hears('(visa|Visa|VISA)', function (BotMan $bot) {
            // Ask the user to select a visa type
            $bot->ask('Which type of visa are you interested in?', function ($answer) use ($bot) {
                $selectedVisa = $answer->getText();
                $bot->reply("Great! You selected $selectedVisa.");
            }, [
                Button::create('Tourist Visa')->value('tourist'),
                Button::create('Work Visa')->value('work'),
            ]);
        });

        $botman->hears('(hey|hello|hi)', function (BotMan $bot) {
            $bot->reply('Hi there! How can I help you?');
        });

        $botman->hears('weather', function (BotMan $bot) {
            $bot->reply('Sure, where are you located?');
        });

        $botman->hears('I want to Create VISA', function (BotMan $bot) {
            $bot->reply('Sure, where are you located?');
        });

        // Add more intent-based conversations and responses here

        $botman->fallback(function (BotMan $bot) {
            $bot->reply('I m sorry, I didnt understand that');
        });

        $botman->listen();
    }



    public function askName($botman)
    {
        $botman->ask("Hello! What is Your Name?",function(Answer $answer){
            $name = $answer->getText();

            $this->say('Nice to meet you '.$name);
        });
    }

}
