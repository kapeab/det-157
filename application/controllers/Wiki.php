<?php

class Wiki extends CI_Controller{
    function __construct()
    {
        parent::__construct();
        $this->load->library('session'); 
        
        if( $this->ion_auth->logged_in() )
        {
            $this->load->model('wiki_model');
        }
        else
        {
            redirect('login/view');
        }
    } 

    /*
     * Listing of wiki
     */
    function view()
    {
        $data['title'] = 'Detachment Wiki';
        $data['wikis'] = $this->wiki_model->get_all_wikis();
        $data['admin'] = $this->session->userdata('admin');

        $this->load->view('templates/header', $data);
        $this->load->view('wikihome');
        $this->load->view('templates/footer'); 
    }

    /*
     * Adding a new wiki
     */
    function add()
    {   
        if(isset($_POST) && count($_POST) > 0)     
        {   
            $params = array(
				'name' => $this->input->post('name'),
            );
            
            $this->wiki_model->add_wiki($params);
            redirect('wiki/view');
        }
        else
        {            
            show_error("You must give the wiki a title.");
        }
    }  

    /*
     * Editing a wiki
     */
    function edit()
    {       
        if( $this->input->post('wiki') !== null )
        {
            $data['title'] = 'Edit Detachment Wiki';
            
            $data['wiki'] = $this->wiki_model->get_wiki($this->input->post('wiki'));

            $this->load->view('templates/header', $data);
            $this->load->view('editwiki');
            $this->load->view('templates/footer'); 
        }
        else
        {
            show_error('The wiki you are trying to edit does not exist.');
        }
    } 
    
    /*
     * Saves changes made to the wiki.
     */
    function save()
    {
        if( $this->input->post('modifiedwiki') !== null )
        {
            $params = array(
                'body' => $this->input->post('savewiki'),
            );
            
            $this->wiki_model->update_wiki($this->input->post('modifiedwiki'), $params);

            $data['wikis'] = $this->wiki_model->get_all_wikis();
            $data['title'] = "Documentation";
            $this->load->view('templates/header', $data);
            $this->load->view('wikihome');
            $this->load->view('templates/footer'); 
        }
        else
        {
            show_error('The wiki you are trying to edit does not exist.');
        }
    }

    /*
     * Deleting wiki
     */
    function remove()
    {
        $wiki = $this->wiki_model->get_wiki($this->input->post('wiki'));

        // check if the wiki exists before trying to delete it
        if(isset($wiki['id']))
        {
            $this->wiki_model->delete_wiki($this->input->post('wiki'));
            redirect('wiki/view');
        }
        else
        {
            show_error('The wiki you are trying to delete does not exist.');
        }
    }
    
}
