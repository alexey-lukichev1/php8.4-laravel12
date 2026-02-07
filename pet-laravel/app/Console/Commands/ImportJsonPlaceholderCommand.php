<?php

namespace App\Console\Commands;

use App\Components\ImportDataClient;
use App\Models\Book;
use Illuminate\Console\Command;

use function Laravel\Prompts\progress;

class ImportJsonPlaceholderCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'import:jsonplaceholder';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Get data from jsonplaceholder';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $import = new ImportDataClient();
        $response = $import->client->request('GET', 'posts');
        $data = json_decode($response->getBody()->getContents(), true);
        $posts = $data['posts'] ?? $data;

        foreach ($posts as $item) {
            $progress = substr($item['body'] ?? '', 0, 255);

            Book::firstOrCreate([
                'title' => $item['title']
            ], [
                'title' => $item['title'],
                'progress' => $progress,
                'views' => $item['views'] ?? rand(1, 10),
                'release_date' => '2026-02-03',
            ]);
        }
        $this->info("Imported " . count($posts) . " posts");
    }
}
