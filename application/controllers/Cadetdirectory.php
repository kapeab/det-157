<?php

class Cadetdirectory extends CI_Controller{
    function __construct()
    {
        parent::__construct();
        $this->load->library('session'); 
        
        if( !$this->ion_auth->logged_in() )
        {
            redirect('login/view');
        }
    } 

    /*
     * Shows all cadets based on a given major.
     */
    function major()
    {
//        TODO: Make this searchable again
        $data['title'] = 'Cadet Directory';

        $data['users'] = $this->ion_auth->users()->row();
        $data['selected'] = $this->input->post('showcadets');

        $this->load->view('templates/header', $data);
        $this->load->view('directory');
        $this->load->view('templates/footer'); 
    }
    
    /*
     * Shows all of the cadets in the detachment.
     */
    function view()
    {
        $data['title'] = 'Cadet Directory';
        $data['users'] = $this->ion_auth->users()->result();

        $this->load->view('templates/header', $data);
        $this->load->view('directory');
        $this->load->view('templates/footer'); 
    }
    
    /*
     * Shows another cadet's profile.
     */
    function profile()
    {        
        $data['title'] = 'Profile Page';
        
        // Looks for profile picture
        $files = scandir("./images");
        $found = false;
        foreach($files as $file)
        {
            $info = pathinfo($file);
            if($info['filename'] == $this->input->post('rin'))
            {
                $data['picture'] = $file; 
                $found = true;
            }
        }
        if(!$found)
        {
            $data['picture'] = base_url("images/default.jpeg");
        }
        
        $data['cadet'] = $this->Cadet_model->get_cadet($this->input->post('rin'));
        
        if(strpos($data['cadet']['rank'], "AS") !== false || strpos($data['cadet']['rank'], "None") !== false)
        {
            $data['heading'] = "Cadet " . $data['cadet']['lastName'];
        }
        else
        {
            $data['heading'] = $data['cadet']['rank'] . " " . $data['cadet']['lastName'];
        } 
        
        $data['myprofile'] = false;
        
        $this->load->view('templates/header', $data);
        $this->load->view('cadet/profile');
        $this->load->view('templates/footer');   
    }
    
}
