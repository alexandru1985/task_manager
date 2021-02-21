<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use App\Mail\SendMail;
use Mail;

class SendMailJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $details;
  
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($details)
    {
        $this->details = $details;
        $this->details['message'] = $this->clientDetails();
    }
  

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $email = new SendMail($this->details);
        Mail::to($this->details['email'])->send($email);
    }
    public function clientDetails()
    {
        
        if (!empty($_SERVER['HTTP_CLIENT_IP']))   //check ip from share internet
        {
        $ip=$_SERVER['HTTP_CLIENT_IP'];
        }
        elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR']))   //to check ip is pass from proxy
        {
        $ip=$_SERVER['HTTP_X_FORWARDED_FOR'];
        }
        else
        {
        $ip=$_SERVER['REMOTE_ADDR'];
        }
        $xml = simplexml_load_file("http://www.geoplugin.net/xml.gp?ip=". $ip);

        $result = '';
        $result .= 'IP: '.$xml->geoplugin_request . ',';
        $result .= 'Country: '.$xml->geoplugin_countryName . ',';
        $result .= 'City: '.$xml->geoplugin_city. ',';
        $result .= 'Latitude: '.$xml->geoplugin_latitude. ',';
        $result .= 'Longitude: '.$xml->geoplugin_longitude. ',';
        $result .= 'Timezone: '.$xml->geoplugin_timezone;
        return $result;
    }
}
