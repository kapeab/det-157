<?php

class Cadetevent extends CI_Controller{
    function __construct()
    {
        parent::__construct();
        $this->load->library('session'); 
        
        if( $this->ion_auth->logged_in() )
        {
            $this->load->model('Cadetevent_model');
        }
        else
        {
            redirect('login/view');
        }
    } 

    /*
     * Loads the cadet event page.
     */
    function view()
    {
        if(isset($_POST) && count($_POST) > 0)
        {
            redirect('cadetevent/event/' . $this->input->post('event'));
        }
        else
        {
            show_error("You must provide an event to view");
        }
    }

    /*
     * Displays the event
     */
    function event($event)
    {
        $data['event'] = $this->Cadetevent_model->get_cadetevent( $event );

        $data['title'] = 'Set Attendance';

        // Loads the home page
        $this->load->view('templates/header', $data);
        $this->load->view('attendance/attend');
        $this->load->view('templates/footer');
    }

    /*
     * Adds a new cadet event.
     */
    function add()
    {   
        if(isset($_POST) && count($_POST) > 0)     
        {
            $user = $this->ion_auth->user()->row();

            if($this->input->post('type') === "pt")
            {
                $pt = 1;
                $llab = 0;
            }
            else if($this->input->post('type') === "llab")
            {
                $pt = 0;
                $llab = 1;
            }
            else
            {
                $pt = 0;
                $llab = 0;
            }
            $params = array(
				'name' => $this->input->post('name'),
				'date' => $this->input->post('date'),
				'pt' => $pt,
				'llab' => $llab,
                'created_by' => $user->id,
            );
            
            $this->Cadetevent_model->add_cadetevent($params);
            redirect('attendance/view');
        }
        else
        {            
            show_error("Something went wrong with adding a new cadet event");
        }
    }
}
