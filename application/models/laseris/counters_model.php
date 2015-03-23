<?php
class Counters_model extends CI_Model {

    public function __construct() {
        parent::__construct();
    }

    public function getCounters($ip=false, $domain = false)
    {
        $counters = $this->db->get($this->db->dbprefix('counters'))->row();
        if (!empty($ip))
        {
            $ar_ip = explode(',',str_replace(' ','',$counters->ip));
            if (in_array($ip, $ar_ip))
                return false;
        }

        if (!empty($domain))
        {
            $ar_domain = explode(',',str_replace(' ','',$counters->domain));

            if (in_array($domain, $ar_domain))
            {
                return $counters;
            }
            else
            {
                list($x1,$x2) = explode('.',strrev($domain));
                $xdomain = $x1.'.'.$x2;
                $xdomain = strrev($xdomain);
                $lvl_domain = count(explode('.',$domain));
                if ($lvl_domain == 3 && (in_array('*.'.$xdomain, $ar_domain)))
                {
                    return $counters;
                }
                elseif($lvl_domain == 4 && (in_array('*.*.'.$xdomain, $ar_domain)))
                    return $counters;

                return false;
            }
        }
        return $counters;
    }

}

/* End of file page.php */
/* Location: ./system/application/models/page_model.php */