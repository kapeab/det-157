<?php

class Cadetgroup extends CI_Controller{
    function __construct()
    {
        parent::__construct();
        $this->load->library('session'); 
        
        if( $this->ion_auth->logged_in() )
        {
            $this->load->model('Cadetgroup_model');
            $this->load->model('Cadet_model');
            $this->load->model('Groupmember_model');
        }
        else
        {
            redirect('login/view');
        }
    } 

    /*
     * Shows page to modify and create groups.
     */
    function view()
    {
        $data['title'] = 'Create/Modify Group';
        $data['groups'] = $this->Cadetgroup_model->get_all_groups();

        $data['groupname'] = $this->input->post('groupname');

        $this->load->view('templates/header', $data);
        $this->load->view('admin/addgroup');
        $this->load->view('templates/footer'); 
    }

    /*
     * Adding a new cadetgroup
     */
    function add()
    {   
        if(isset($_POST) && count($_POST) > 0)     
        {   
            $params = array(
				'label' => $this->input->post('label'),
                'description' => $this->input->post('description')
            );
            
            $this->Cadetgroup_model->add_group($params);

            redirect('cadetgroup/view');
        }
        else
        {            
            show_error("No input given!");
        }
    }  
    
    /*
     * Selects a group to be modified.
     */
    function modify()
    {   
        if(isset($_POST) && count($_POST) > 0)     
        {
            $data['curgroup'] = $this->input->post('group');
            $data['groupname'] = $this->Cadetgroup_model->get_group($this->input->post('group'));
            $data['title'] = 'Create/Modify Group';
            $data['groups'] = $this->Cadetgroup_model->get_all_groups();
            
            // Finds each group member 
            $cadets = $this->Cadet_model->get_all_cadets();
            $members = array();
            $nonmembers = array();
            foreach( $cadets as $cadet )
            {
                if( $this->Groupmember_model->in_group( $data['curgroup'], $cadet['rin'] ) )
                {
                    // Cadet is in group
                    $members[] = $cadet;
                }
                else
                {
                    // Cadet is not in group
                    $nonmembers[] = $cadet;
                }
            }
            $data['members'] = $members;
            $data['nonmembers'] = $nonmembers;

            echo json_encode($data);
        }
        else
        {            
            show_error("No input given!");
        }
    }  
    
    /*
     * Adds cadet's to a group.
     */
    function addmembers()
    {
        $cadets = $this->input->post('cadets');
        $group = $this->input->post('group');
        
        if( $cadets !== null && $group !== null )
        {
            foreach( $cadets as $cadet )
            {
                $params = array(
                    'groupID' => $group,
                    'rin' => $cadet

                );
            
                $cadetgroup_id = $this->Groupmember_model->add_groupmember($params);
                
            }
            
            $data['title'] = 'Create/Modify Group';
            $data['groups'] = $this->Cadetgroup_model->get_all_groups();
            
            // Finds each group member 
            $cadets = $this->Cadet_model->get_all_cadets();
            $members = array();
            $nonmembers = array();
            foreach( $cadets as $cadet )
            {
                if( $this->Groupmember_model->in_group( $group, $cadet['rin'] ) )
                {
                    // Cadet is in group
                    $members[] = $cadet;
                }
                else
                {
                    // Cadet is not in group
                    $nonmembers[] = $cadet;
                }
            }
            $data['members'] = $members;
            $data['nonmembers'] = $nonmembers;
            $data['curgroup'] = $group;
            $data['groupname'] = $this->Cadetgroup_model->get_group($group);

            $this->load->view('templates/header', $data);
            $this->load->view('admin/addgroup');
            $this->load->view('templates/footer');  
        }
        else
        {
            show_error("You must select at least one cadet to be added to the group. Also you must select a group.");
        }
    }
    

    /*
     * Removes cadet's from a group.
     */
    function removemembers()
    {
        $cadets = $this->input->post('cadets');
        $group = $this->input->post('group');
        
        if( $cadets !== null && $group !== null )
        {
            foreach( $cadets as $cadet )
            {
                $cadetgroup_id = $this->Groupmember_model->delete_groupmember($cadet, $group);
            }
            
            $data['title'] = 'Create/Modify Group';
            $data['groups'] = $this->Cadetgroup_model->get_all_groups();
            
            // Finds each group member 
            $cadets = $this->Cadet_model->get_all_cadets();
            $members = array();
            $nonmembers = array();
            foreach( $cadets as $cadet )
            {
                if( $this->Groupmember_model->in_group( $group, $cadet['rin'] ) )
                {
                    // Cadet is in group
                    $members[] = $cadet;
                }
                else
                {
                    // Cadet is not in group
                    $nonmembers[] = $cadet;
                }
            }
            $data['members'] = $members;
            $data['nonmembers'] = $nonmembers;
            $data['curgroup'] = $group;
            $data['groupname'] = $this->Cadetgroup_model->get_group($group);

            $this->load->view('templates/header', $data);
            $this->load->view('admin/addgroup');
            $this->load->view('templates/footer');  
        }
        else
        {
            show_error("You must select at least one cadet to be added to the group. Also you must select a group.");
        }
    }
}
