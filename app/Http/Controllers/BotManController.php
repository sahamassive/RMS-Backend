<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
namespace App\Http\Controllers;

use BotMan\BotMan\BotMan;
use BotMan\BotMan\Messages\Incoming\Answer;
use BotMan\BotMan\Messages\Outgoing\Question;
use BotMan\BotMan\Messages\Outgoing\Actions\Button;

class BotManController extends Controller
{
    public function handle()
    {
        $botman = app('botman');

        $botman->hears('Hello', function (BotMan $bot) {
            $bot->reply('Hi there!');
        });

        $botman->hears('What is your name?', function (BotMan $bot) {
            $bot->reply('My name is Botman.');
        });

        $botman->hears('Tell me a joke', function (BotMan $bot) {
            $jokes = [
                'Why did the tomato turn red? Because it saw the salad dressing!',
                'What did the janitor say when he jumped out of the closet? "Supplies!"',
                'Why did the scarecrow win an award? Because he was outstanding in his field!',
            ];
            $bot->reply($jokes[array_rand($jokes)]);
        });

        $botman->fallback(function (BotMan $bot) {
            $bot->reply('Sorry, I did not understand your message.');
        });

        // $botman->listen();
        return response()->json($botman->listen());
    }
}
