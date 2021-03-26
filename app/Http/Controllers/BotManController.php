<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Auth;

use BotMan\BotMan\BotMan;
use Illuminate\Http\Request;
use App\Conversations\ExampleConversation;

class BotManController extends Controller
{
    public function handle()
    {
        $botman = app('botman');




        $botman->hears('Hello', function($bot){
            $bot->typesAndWaits(0.5);
            $bot->reply('Welcome '. Auth::user()['name']);


            $this->calc_operation($bot);

        });



        $botman->listen();



    }

    public function calc_operation($bot)
    {
        $subRegExp = '/(?<operator>subtract)\s+(?<first>\d+)\s+from\s+(?<second>\d+)/';
        $addRegExp = '/(?<operator>add|multiply)\s+(?<first>\d+)\s+(to|and)\s+(?<second>\d+)/';
        $divRegExp = '/(?<operator>divide)\s+(?<first>\d+)\s+(by)\s+(?<second>\d+)/';
        $numRegExp = '/(\d+)(?:\s*)([\+\-\*\/])(?:\s*)(\d+)/';

        $repeat_operation   = function ($bot) { $this->repeat_operation($bot); };
        $calc_operation     = function ($bot) { $this->calc_operation($bot); };

        $bot->ask('Please Enter You Operation', function ($answer, $bot) use ($subRegExp, $addRegExp, $divRegExp, $numRegExp, $repeat_operation, $calc_operation) {

            $success = false;

            if (preg_match($numRegExp, $answer->getText(), $matches)) {
                $operator = $matches[2];
                switch ($operator) {
                    case '+':
                        $p = $matches[1] + $matches[3];
                        $bot->say("It's  " . $p);
                        $success = true;
                        break;
                    case '-':
                        $p = $matches[1] - $matches[3];
                        $bot->say("It's  " . $p);
                        $success = true;
                        break;
                    case '*':
                        $p = $matches[1] * $matches[3];
                        $bot->say("It's  " . $p);
                        $success = true;
                        break;
                    case '/':
                        $p = $matches[1] / $matches[3];
                        $bot->say("It's  " . $p);
                        $success = true;
                        break;
                    default:
                        $success = false;
                        break;

                }
            }


            if (preg_match($addRegExp, $answer->getText(), $matches)) {
                $operator = $matches['operator'];
                switch ($operator) {
                    case 'add':
                        $p = $matches['first'] + $matches['second'];
                        $bot->say("You means:  " . $matches['first'] . ' + ' . $matches['second']);
                        $bot->say("It's  " . $p);
                        $success = true;
                        break;
                    case 'multiply':
                        $p = $matches['first'] * $matches['second'];
                        $bot->say("You means:  " . $matches['first'] . ' * ' . $matches['second']);
                        $bot->say("It's  " . $p);
                        $success = true;
                        break;
                    default:
                        $success = false;
                        break;

                }

            }
            if (preg_match($subRegExp, $answer->getText(), $matches)) {
                $operator = $matches['operator'];
                switch ($operator) {
                    case 'subtract':
                        $p = $matches['second'] - $matches['first'];
                        $bot->say("You means:  " . $matches['first'] . ' - ' . $matches['second']);
                        $bot->say("It's  " . $p);
                        $success = true;
                        break;
                    default:
                        $success = false;
                        break;
                }

            }
            if (preg_match($divRegExp, $answer->getText(), $matches)) {
                $operator = $matches['operator'];
                switch ($operator) {
                    case 'divide':
                        $p = $matches['first'] / $matches['second'];
                        $bot->say("You means:  " . $matches['first'] . ' / ' . $matches['second']);
                        $bot->say("It's  " . $p);
                        $success = true;
                        break;
                    default:
                        $success = false;
                        break;

                }

            }
            if( $success )
            {
                $repeat_operation($bot);
            }
            else
            {
                $bot->say('Wrong Input');
                $calc_operation($bot);
            }
        });
    }

    public function repeat_operation($bot)
    {
        $calc_operation    = function ($bot) { $this->calc_operation($bot); };
        $repeat_ask        = function ($bot) { $this->repeat_ask($bot); };
        $repeat_operation  = function ($bot) { $this->repeat_operation($bot); };

        $bot->ask("Please send 1 if you think my answer is correct, 2 if it's wrong, or 3 if you don't know. ", function($answer, $bot) use ($calc_operation, $repeat_ask, $repeat_operation){

            $ans = $answer->getText();

                switch($ans)
                {
                    case '1':
                        $bot->say('OH, Great.');
                        $repeat_ask($bot);
                        break;
                    case '2':
                        $bot->say('Try again.');
                        $calc_operation($bot);
                        break;
                    case '3':
                        $bot->say("So it's correct");
                        $repeat_ask($bot);
                        break;
                    default:
                        $bot->say('Wrong Input');
                        $repeat_operation($bot);
                        break;
                }
        });
    }

    public function repeat_ask($bot)
    {

        $calc_operation   = function ($bot) { $this->calc_operation($bot); };
        $repeat_ask       = function ($bot) { $this->repeat_ask($bot); };

        $bot->ask("If you have another question, send 1 or send 2 to end this session.", function($answer, $bot) use ($calc_operation, $repeat_ask){

            $ans        = $answer->getText();

            switch($ans)
            {
                case '1':
                    $calc_operation($bot);
                    break;
                case '2':
                    $bot->say('Good-Bye! Have a nice day.');
                    break;
                default:
                    $bot->say('Wrong Input');
                    $repeat_ask($bot);
                    break;
            }
        });
    }

    public function tinker()
    {
        return view('tinker');
    }

    public function startConversation(BotMan $bot)
    {
        $bot->startConversation(new ExampleConversation());
    }
}
