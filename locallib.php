    <?php
// This file is part of Moodle - http://moodle.org/
//
// Moodle is free software: you can redistribute it and/or modify
// it under the terms of the GNU General Public License as published by
// the Free Software Foundation, either version 3 of the License, or
// (at your option) any later version.
//
// Moodle is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.
//
// You should have received a copy of the GNU General Public License
// along with Moodle.  If not, see <http://www.gnu.org/licenses/>.

/**
 * Internal library of functions for module eventials
 *
 * All the eventials specific functions, needed to implement the module
 * logic, should go here. Never include this file from your lib.php!
 *
 * @package    mod_eventials
 * @copyright  2016 Your Name <your@email.address>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();
require_once(dirname(__FILE__).'/vendor/autoload.php');

function eventials_login(){
    global $DB;
    $conf = $DB->get_record('eventials_access_token',[]);


    $client = new \GuzzleHttp\Client();
    $res = $client->request('POST', 'https://api.eventials.com/v1/oauth/token', [
        'body' => "grant_type=client_credentials&client_id=3e1dcdf33acd445e933b50394b18f024&client_secret=82f39262de5741d5823261b27c3bbb09",
        'headers' => ['Content-Type'=>"application/x-www-form-urlencoded"]
    ]);

    if ($res->getStatusCode() >= 400)
        throw new Exception("Error trying to add new webinar. HTTP code {$res->getStatusCode()}. - {$res->getBody()}");
    return json_decode($res->getBody())->access_token;
}

function eventials_add_user_to_webinar($email,$webinar_id){
    $token = eventials_login();
    $client = new \GuzzleHttp\Client();

    $client->request('POST', "https://api.eventials.com/v1/webinars/{$webinar_id}/access-control", [
        'json' =>  [
            'send_invitation'=>false,
            'emails' => [$email]
        ],
        'headers' => ['Authorization'=>"Bearer {$token}"]
    ]);
}

function eventials_schedule_webinar($title, $start_time, $duration, $description, $timezone_id=69){
    $token = eventials_login();
    $client = new \GuzzleHttp\Client();

    $res = $client->request('POST', 'https://api.eventials.com/v1/webinars', [
        'json' =>  [
            'title'=>$title,
            'start_time' => $start_time,
            'duration' => $duration,
            'description' => $description,
            'category_id' => 6,
            'timezone_id' => $timezone_id,
            'is_public' => false,
            'is_draft' => false,
            'embed_enabled' => true,
            'ticket_price' => 0,
            'metadata' => new class{}
        ],
        'headers' => ['Authorization'=>"Bearer {$token}"]
    ]);
    if ($res->getStatusCode() >= 400)
        throw new Exception("Error trying to add new webinar. HTTP code {$res->getStatusCode()}. - {$res->getBody()}");
    return json_decode($res->getBody());
}