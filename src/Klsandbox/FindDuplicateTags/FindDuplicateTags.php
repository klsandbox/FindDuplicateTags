<?php

namespace Klsandbox\FindDuplicateTags;

use Illuminate\Console\Command;

// From https://gist.github.com/lucasmichot/7313220
class FindDuplicateTags extends Command {

    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'blade:findduplicates';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Find duplicate tags, which could be merged into a common view';

    protected function getArguments() {
        return [['unused-key' => 'threshold']];
    }

    /**
     * Execute the console command.
     *
     * @return void
     */
    public function fire() {
        $threshold = $this->argument('threshold');
        if (!$threshold)
        {
            $threshold = 5;
        }
        
        $this->comment("Finding duplicates - threshold " . $threshold);

        $views = [];
        foreach (\Config::get('view.paths') as $folder) {
            $finder = \Symfony\Component\Finder\Finder::create()->in($folder);
            foreach ($finder->files() as $i) {
                $html = file_get_contents($i->getPathname());

                preg_match_all('/<(\w+)[^>]*>[^>]*<\/\1>/', $html, $out);
                $views[$i->getRelativePathname()] = $out[0];
            }
        }

        $pair = [];
        foreach ($views as $k1 => $v1) {
            foreach ($views as $k2 => $v2) {
                if ($k1 <= $k2)
                {
                    continue;
                }
                
                $intersect = array_intersect($v1, $v2);
                if (count($intersect) > $threshold)
                {
                    $pair[$k1][$k2] = $intersect;
                }
            }
        }

        dd($pair);
    }

}
