<?php

namespace App\Command;

use App\Entity\Characters;
use App\Entity\Movies;
use App\Entity\MoviesCharacters;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Doctrine\Persistence\ManagerRegistry;
use Exception;

#[AsCommand(
    name: 'starwars',
    description: 'Add a short description for your command',
)]
class StarwarsCommand extends Command
{

    public function __construct(ManagerRegistry $doctrine)
    {
        $this->doctrine = $doctrine;

        parent::__construct();
    }

    protected function configure(): void
    {
        $this
            ->addArgument('arg1', InputArgument::OPTIONAL, 'Command that define the action. Use "import" to import the characters for the test.')
            ->addOption('option1', null, InputOption::VALUE_NONE, 'Option description')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        $arg1 = $input->getArgument('arg1');

        if ($arg1 == 'import') {
            //$io->note(sprintf('You passed an argument: %s', $arg1));
            try{
                $success = $this->importMovies();
                $success = $this->importCharacters();
            } catch(Exception $e) {
                $io->error($e->getMessage() . "| fl:" . $e->getFile() ."ln: " . $e->getLine());
            }
            $success ? $io->success('The characters are now in our database') : $io->error('Something goes wrong');
        } else {
            $io->error("The argument $arg1 does not exist. Please use a correct one. Use --help to get more information.");
        }

        // Intersting --help is already created by symfony XD
        // if ($input->getOption('help')) {
        //     $io->note(sprintf('Help instrucctions: \n '));
        // }

        

        return Command::SUCCESS;
    }

    private function importMovies() : bool {
        //We will get the star wars movies
        $data = $this->callApi('films');   

        if(!is_array($data) || empty($data)) return false; //@TODO: Throw a notification to IT department, probably the api is death! : (

        $em = $this->doctrine->getManager();
        foreach ($data as $filmData) {
            $movie = new Movies();
            $movie->setName($filmData['title']);

            $em->persist($movie);
            $em->flush();
        }
        return true;
    }

    private function importCharacters(int $number=30) : bool {
        $page=1;
        //We will get the star wars characters
        $data = $this->callApi('people', $page);

        if(!is_array($data) || empty($data)) return false; //@TODO: Throw a notification to IT department, probably the api is death! : (

        $em = $this->doctrine->getManager();
        for ($count=0; $count < $number; $count++) {
            if(!isset($data[$count])) {
                $extraData = $this->callApi('people', ++$page);

                if(!is_array($data) || empty($data)) return false; //@TODO: Throw a notification to IT department, probably the api is death! : (

                $data = array_merge($data,$extraData);
            }
            $characterData=$data[$count];

            $character = new Characters();
            $character->setName($characterData['name']);
            $character->setMass(intval($characterData['mass']));
            $character->setHeight(intval($characterData['height']));
            $character->setGender($characterData['gender']);
            $character->setPicture(''); //The api don'give to us any picture
            $em->persist($character);
            $em->flush();


            foreach($characterData['films'] as $film) {
                $movieCharacter = new MoviesCharacters();
                $movieCharacter->setMovieId($film[strlen($film)-2]);
                $movieCharacter->setCharacterId($character->getId());

                $em->persist($movieCharacter);
                $em->flush();
            }

        }
        return true;
    }
    private function callApi(string $param, $page=1) {
        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => "https://swapi.dev/api/$param/?page=$page",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'GET',
        ));
        //region for curl call
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
        //endregion
        $response = curl_exec($curl);
        $ce= curl_error($curl);
        if($ce) {
            //@TODO: Throw a notification to IT department, with the $ce message
            return false;
        }
        curl_close($curl);

        return json_decode($response, true)['results'];
    }
}
