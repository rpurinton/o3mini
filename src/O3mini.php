<?php

namespace RPurinton\O3mini;

use RPurinton\OpenAI;

class O3mini
{
    const PROMPT = "o3-mini> ";
    const THINKING = "🧠\033[0;33mThinking...\033[0m";

    private OpenAI $ai;

    public function __construct()
    {
        $this->ai = OpenAI::connect();
    }

    public static function connect()
    {
        return (new self)->run();
    }

    public function run()
    {
        while (true) {
            echo self::PROMPT;
            $input = trim(fgets(STDIN));
            if($this->command($input)) continue;
            echo self::THINKING;
            $response = $this->ai->ask($input);
            $wrapped = wordwrap($response, max(((int)exec('tput cols')) - 1, 40), "\n", true);
            echo "\r\033[0;33m" . $wrapped . "\033[0m\n";
        }
    }

    public function command($input): bool
    {
         switch ($input) {
             case "exit":
                 exit(0);
             case "clear":
                 $this->ai->reload();
                 echo "\033[2J\033[1;1H";
                 return true;
         }
         return false;
    }
}
