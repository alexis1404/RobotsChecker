<?php

namespace App\Http\Controllers;

use App\Services\CheckRobotsService;
use App\Services\RobotMessageProcessorService;
use Illuminate\Http\Request;

class MainCheckRobotsController extends Controller
{
    private $check_robots;
    private $message_processor;

    public function __construct(CheckRobotsService $checkRobots, RobotMessageProcessorService $processorService)
    {
        $this->check_robots = $checkRobots;
        $this->message_processor = $processorService;
    }

    public function index()
    {
        return view('custom_pages.main_check_page');
    }

    public function checkUrl(Request $request)
    {
        $check_list = $this->check_robots->checkRobotsForUrl($request->url);

        if(!$check_list['valid_url']){

            $message = 'URL не прошел валидацию! Попробуйдте ввести URL еще раз';

            return redirect('/')->with('message', $message);

        }else{

            return view('custom_pages.main_check_page', compact('check_list'));
        }
    }

    public function saveAudit(Request $request)
    {
        $check_status = [
            'status_code' => $request->status_code,
            'valid_url' => $request->valid_url,
            'hosts_dir' => $request->hosts_dir,
            'sitemap' => $request->sitemap,
            'statusfail' => $request->statusfail,
            'no_robots' => $request->no_robots,
            'file_size' => $request->file_size
        ];

        $this->message_processor->form_message($check_status);
    }
}
