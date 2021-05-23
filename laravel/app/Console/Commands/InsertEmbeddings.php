<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;

class InsertEmbeddings extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'SR:InsertEmbeddings';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Inserts embeddings to the database.';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
		$userIds = scandir("storage/embeddings/dev");
		
		unset($userIds[0]);
		unset($userIds[1]);
		$i = 1;
		foreach($userIds as $userId){
			echo $i . ". " . $userId . "\r\n";
			$i++;
			
			$user = new \App\Models\User;
			$user->userId = $userId;
			$user->name = $userId;
			$user->email = $userId . "@speaker.test";
			$user->password = "";
			$user->save();
			
			
			
			$embeddingIds = scandir("storage/embeddings/DB_embds/dev/" . $userId);
			unset($embeddingIds[0]);
			unset($embeddingIds[1]);
			
			
			foreach($embeddingIds as $embeddingId){
				$filename = "storage/embeddings/DB_embds/dev/" . $userId . "/" . $embeddingId;
				
				$myfile = fopen($filename, "r") or die("Unable to open file!");
				$content = fread($myfile,filesize($filename));
				fclose($myfile);
				
				$embeddingId = str_replace(".txt", "", $embeddingId);
				
				$embedding = new \App\Models\Embedding;
				$embedding->embeddingId = $embeddingId;
				$embedding->userId = $userId;
				$embedding->content = $content;
				$embedding->save();
				
				echo $userId . " - " . $embeddingId . "\r\n";
			}
			
			echo "\r\n";
		}
		
		
        return 0;
    }
}
