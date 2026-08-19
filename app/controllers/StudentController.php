<?php
defined('PREVENT_DIRECT_ACCESS') OR exit('No direct script access allowed');

class StudentController extends Controller {

    public function index() {
        if (session_status() !== PHP_SESSION_ACTIVE) {
            session_start();
        }
        $_SESSION['ad_access_granted'] = true;

        $this->call->view('student_home');
    }

    public function profile() {
        $student = [
            'student_id'      => 'MCC2024-00019',
            'name'            => 'Andyella U. Dimalibot',
            'course'          => 'BS Information Technology',
            'year'            => '3rd Year',
            'section'         => 'F1',
            'email'           => 'andyellaud@gmail.com',
            'contact'         => '0962 907 9671',
            'address'         => 'Ilag, San Teodoro, Oriental Mindoro',
            'status'          => 'Access Granted',
            'avatar_initials' => 'AD',
            'description'     => "Hi! I am Andyella, a 3rd year BSIT student from Mindoro State University - Calapan Campus, who loves to explore and who fears being average. ://",
            'skills' => [
                            ['name' => 'Video Editing',          'level' => 80],
                            ['name' => 'Visual Design',          'level' => 65],
                            ['name' => 'Communication Skills',   'level' => 75],
                            ['name' => 'Brainstorming',          'level' => 60],
                        ],
            'hobbies'         => ['Badminton', 'Reading', 'Writing'],
            'socials'         => [
                [
                    'platform' => 'Facebook',
                    'handle'   => 'Andyella Dimalibot',
                    'url'      => 'https://www.facebook.com/share/1Dd6sN42zF/',
                    'icon'     => 'facebook',
                ],
                [
                    'platform' => 'Instagram',
                    'handle'   => '@adiiiyel',
                    'url'      => 'https://www.instagram.com/adiiiyel',
                    'icon'     => 'instagram',
                ],
                [
                    'platform' => 'Telegram',
                    'handle'   => '@adiiiyel',
                    'url'      => 'https://t.me/adiiiyel',
                    'icon'     => 'telegram',
                ],
                [
                    'platform' => 'Discord',
                    'handle'   => '@adiiiyel',
                    'url'      => 'https://discordapp.com/users/1189244273636425768/',
                    'icon'     => 'discord',
                ],
            ],
        ];

        $this->call->view('student_info', $student);
    }
}