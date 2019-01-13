<?php
/*
 * Generated by CRUDigniter v3.2
 * www.crudigniter.com
 */

class Batchemail extends CI_Controller{
    function __construct()
    {
        parent::__construct();
        $this->load->model('Batch_email_model');
        $this->load->library('email');
    }

    /*
     * Sends all scheduled emails for the day
     */
    function schedule()
    {
        foreach( $this->Batch_email_model->get_all_batchemails() as $email )
        {
            if( $email['day'] === date("Y-m-d") )
            {
                $this->email->to($email['to']);
                $this->email->from($email['from'],$email['title']);
                $this->email->subject($email['subject']);
                $this->email->message($email['message']);
                $this->email->send();

                // Removes scheduled email from DB after sending it
                $this->Batch_email_model->delete_batchemail( $email['uid'] );
            }
        }
    }
}