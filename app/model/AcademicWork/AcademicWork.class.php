<?php
use Adianti\Database\TRecord;



class AcademicWork extends TRecord{

    const TABLENAME = 'academics_works';
    const PRIMARYKEY = 'id';
    const IDPOLICY = 'serial';


    public function __construct($id = null){

        parent::__construct($id);
        parent::addAttribute('title');
        parent::addAttribute('author');
        parent::addAttribute('advisor');
        // parent::addAttribute('co_advisor');
        parent::addAttribute('abstract');
        parent::addAttribute('keywords');
        parent::addAttribute('presentation_date');
        parent::addAttribute('research_area');
        parent::addAttribute('file');
        parent::addAttribute('user_id');
        parent::addAttribute('isApproved');

    }





}