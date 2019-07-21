<?php


namespace App\Command;

use App\Services\GameFetcher;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class FetchGameCommand extends Command
{

    /**
     * @var GameFetcher
     */
    private $gameFetcher;

    public function __construct(GameFetcher $gameFetcher)
    {
        parent::__construct();
        $this->gameFetcher = $gameFetcher;
    }

    public function configure()
    {
        $this->setName('game:fetch')
            ->setDescription('fetch game from api')
        ;
    }

    public function execute(InputInterface $input, OutputInterface $output){
        $gamesList = [
            "Superfighters Deluxe",
            "Persona 5",
            "Horizon Zero Dawn",
            "Super Mario Odyssey",
            "Uncharted 4: A Thief's End",
            "Divinity: Original Sin II",
            "Super Smash Bros. Ultimate",
            "Celeste",
            "NieR: Automata",
            "Hollow Knight",
            "Doki Doki Literature Club",
            "Monster Hunter: World",
            "RimWorld",
            "Xenoblade Chronicles 2",
            "INSIDE",
            "Assassin's Creed: Odyssey",
            "Dark Souls III"
        ];
        $this->gameFetcher->fetchGames($gamesList);
    }


}