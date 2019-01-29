<?php
/* 
 * Generated by CRUDigniter v3.2 
 * www.crudigniter.com
 */
 
class Announcement extends CI_Controller{
    function __construct()
    {
        parent::__construct();
        $this->load->library('session'); 
        
        if( $this->session->userdata('login') === true )
        {
            $this->load->model('Announcement_model');
        }
        else
        {
            redirect('login/view');
        }
    } 

    /*
     * Listing of announcement
     */
    function index()
    {
        $data['announcement'] = $this->Announcement_model->get_all_announcement();
        
        $data['_view'] = 'announcement/index';
        $this->load->view('layouts/main',$data);
    }
    
    /*
     * Sends the announcement.
     */
    function post()
    {
        $this->load->helper('form');
        
        if( $this->input->post('body') != null && $this->input->post('subject') != null)
        {
            // Goes to each selected group and sends announcement as email
            if( $this->input->post('groups') !== null )
            {
                // Encrypts the email
                $this->load->library('encryption');

                // Load email library
                $this->load->library('email');

                $this->load->model('groupmember_model');
                $this->load->model('cadet_model');
                $this->load->model('batch_email_model');

                $recipients = array();
                
                foreach( $this->input->post('groups') as $group )
                {
                    $data['members'] =  $this->groupmember_model->get_all_groupmembers( $group );
                    foreach( $data['members'] as $member )
                    {
                        // Gets the cadet who needs to be sent an email
                        $cadetemail = $this->cadet_model->get_cadet( $member['rin'] );

                        // Creates an email to be send
                        $params = array(
                            'day'           => date("Y-m-d"),
                            'to'            => $cadetemail['primaryEmail'],
                            'from'          => "afrotcdet550@gmail.com",
                            'subject'       => $this->input->post('subject'),
                            'message'       => $this->input->post('body'),
                            'title'         => $this->input->post('title')
                        );
                        $this->batch_email_model->add_batchemail($params);
                    }
                }

            }

            $params = array(
                'title'     => $this->input->post('title'),
                'subject'   => $this->input->post('subject'),
                'body'      => $this->input->post('body'),
                'createdBy' => $this->session->userdata('rin')
            );
            
            $id = $this->Announcement_model->add_announcement( $params );

            // Sends the announcement to groupMe
            $url = "https://api.groupme.com/v3/bots/post";
            $fields = [
                'bot_id'    => "b83da12e82339a292c0173442d",
                'text'      => "Title: " . $this->input->post('title') . " 
                Subject: " . $this->input->post('subject') . "
                
                Link: " . site_url("announcement/page/" . $id ),
            ];
            $fields_string = http_build_query($fields);
            $ch = curl_init();
            curl_setopt($ch,CURLOPT_URL, $url);
            curl_setopt($ch,CURLOPT_POST, count($fields));
            curl_setopt($ch,CURLOPT_POSTFIELDS, $fields_string);
            curl_setopt($ch,CURLOPT_RETURNTRANSFER, true);
            curl_exec($ch);

            // Goes back to announcement create page
            redirect('announcement/create');
        }
        else
        {
            show_error("The post you are trying to make doesn't have a title/subject/description.");
        }
    }
    
    /*
     * Allows a user to create an announcement.
     */
    function create()
    {
        $data['title'] = 'Make an Announcement';
        $this->load->model('announcement_model');
        $this->load->model('cadetgroup_model');

        $data['announcements'] =  $this->announcement_model->get_all_announcements();
        $data['groups'] = $this->cadetgroup_model->get_all_groups();

        // Loads the home page 
        $this->load->view('templates/header', $data);
        $this->load->view('pages/makepost.php');
        $this->load->view('templates/footer');  
    }

    /*
     * Shows the annoucement page.
     */
    function view( $page = 1 )
    {
        $data['title'] = 'Announcements';
        $this->load->model('announcement_model');
        $this->load->model('cadet_model');
        $this->load->model('acknowledge_post_model');
        $this->load->library("pagination");

        $config = array();
        $config["base_url"] = base_url() . "announcement/view";

        $config["total_rows"] = $this->announcement_model->record_count();
        $config["per_page"] = 10;
        $config["num_tag_open"] = "<li class='page-item'>";
        $config["num_tag_close"] = "</li>";
        $config["cur_tag_open"] = "<li class='page-item active'><a class='page-link'>";
        $config["cur_tag_close"] = '</a></li>';
        $config["full_tag_open"] = "<nav aria-label='navigation' class='nav'><ul class='pagination'>";
        $config["full_tag_close"] = "</ul></nav>";
        $config["first_link"] = "First";
        $config["first_tag_open"] = "<li class='page-item'>";
        $config["first_tag_close"] = "</li>";
        $config["last_link"] = "Last";
        $config["last_tag_open"] = "<li class='page-item'>";
        $config["last_tag_close"] = "</li>";
        $config["next_link"] = "Next";
        $config["next_tag_open"] = "<li class='page-item'>";
        $config["next_tag_close"] = "</li>";
        $config["prev_link"] = "Previous";
        $config["prev_tag_open"] = "<li class='page-item'>";
        $config["prev_tag_close"] = "</li>";
        $config["attributes"] = array('class' => 'page-link');

        $this->pagination->initialize($config);

        $data["announcements"] = $this->announcement_model->get_specific_announcements($config["per_page"], $page);
        $data["links"] = $this->pagination->create_links();
        $data['cadets'] = $this->cadet_model->get_all_cadets();
        $data['ackposts'] = $this->acknowledge_post_model->get_all_acknowledge_posts();

        // Loads the home page 
        $this->load->view('templates/header', $data);
        $this->load->view('pages/announcements.php');
        $this->load->view('templates/footer');   
    }

    /*
  * Shows the annoucement page.
  */
    function page( $page )
    {
        $data['title'] = 'Announcements';
        $this->load->model('announcement_model');
        $this->load->model('cadet_model');

        $data["announcement"] = $this->announcement_model->get_announcement($page);
        $data['cadets'] = $this->cadet_model->get_all_cadets();
        if($data['announcement']['createdBy'] == $this->session->userdata('rin'))
        {
            $data['mypost'] = true;
        }
        else
        {
            $data['mypost'] = false;
        }

        // Loads the home page
        $this->load->view('templates/header', $data);
        $this->load->view('pages/announcement.php');
        $this->load->view('templates/footer');
    }
    
    /*
     * Adding a new announcement
     */
    function add()
    {   
        if(isset($_POST) && count($_POST) > 0)     
        {   
            $params = array(
				'title' => $this->input->post('title'),
				'subject' => $this->input->post('subject'),
				'createdBy' => $this->input->post('createdBy'),
				'date' => $this->input->post('date'),
				'body' => $this->input->post('body'),
            );
            
            $announcement_id = $this->Announcement_model->add_announcement($params);
            redirect('announcement/index');
        }
        else
        {            
            $data['_view'] = 'announcement/add';
            $this->load->view('layouts/main',$data);
        }
    }

    /*
     * Loads the edit announcement page.
     */
    function edit()
    {
        $data['title'] = 'Edit Announcement';
        $data['announcement'] = $this->Announcement_model->get_announcement($this->input->post('announcement'));

        // Loads the home page
        $this->load->view('templates/header', $data);
        $this->load->view('pages/editannouncement.php');
        $this->load->view('templates/footer');
    }

    /*
     * Loads the edit announcement page.
     */
    function update()
    {
        $data['title'] = 'Edit Announcement';
        $announcement = $this->Announcement_model->get_announcement($this->input->post('announcement'));

        $params = array(
            'title'     => $this->input->post('title'),
            'subject'   => $this->input->post('subject'),
            'body'      => $this->input->post('body'),
        );

        $this->Announcement_model->update_announcement( $announcement['uid'], $params );

        redirect("announcement/page/" . $announcement['uid']);
    }

    /*
     * Deleting announcement
     */
    function remove()
    {
        $announcement = $this->Announcement_model->get_announcement($this->input->post('announcement'));

        // check if the announcement exists before trying to delete it
        if(isset($announcement['uid']))
        {
            $this->Announcement_model->delete_announcement($this->input->post('announcement'));
            redirect('cadet/view');
        }
        else
            show_error('The announcement you are trying to delete does not exist.');
    }
    
}
