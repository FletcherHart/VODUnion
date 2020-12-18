<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\Models\Video;
use Symfony\Component\Process\Exception\ProcessFailedException;
use Symfony\Component\Process\Process;

class ProcessVideo implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $video, $hasThumb, $raw;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(Video $video, bool $hasThumb = false, bool $raw = false)
    {
        $this->video = $video;
        $this->hasThumb = $hasThumb;
        $this->raw = $raw;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        if(!$this->raw)
        {
            $cmd = 'C:\ffmpeg\bin\ffmpeg.exe -i C:\xampp\htdocs\VODeo\storage\app\public\videos/'
            . $this->video->storedAt . 
            ' -crf 29 -r 60 C:\xampp\htdocs\VODeo\storage\app\public\stream/' 
            . $this->video->storedAt .
            ' -progress C:\xampp\htdocs\VODeo\storage\app\public\progress.txt';

            pclose(popen("start /B ". $cmd, "r")); 
        }
        
        if(!$this->hasThumb) {
            $cmd = 'ffmpeg -i C:\xampp\htdocs\VODeo\storage\app\public\videos/'
            . $this->video->storedAt . ' -vf scale=300:169 -r 1/1 C:\xampp\htdocs\VODeo\storage\app\public\thumbnails/'.$this->video->storedAt.'.jpeg';

            pclose(popen("start /B ". $cmd, "r"));
        }
    }
}
