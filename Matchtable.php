<script type="text/javascript">
class MatchTable 
{
    var $_table_one_name;
    var $_table_two_name;

    var $_table_one_db_user;
    var $_table_one_db_pass;
    var $_table_one_db_host;
    var $_table_one_db_name;

    var $_table_two_db_user;
    var $_table_two_db_pass;
    var $_table_two_db_host;
    var $_table_two_db_name;

    var $_table_one_columns = array();
    var $_table_two_columns = array();
    var $_table_one_types = array();
    var $_table_two_types = array();

    var $_table_one_link;
    var $_table_two_link;

    var $_isTest;


    function MatchTable($isLive = true)
    {
        $this->_isTest = !$isLive;
    }

    function matchTables($table1, $table2)
    {
        $this->_table_one_name = $table1;
        $this->_table_two_name = $table2;

        if(isset($this->_table_one_db_pass))
        {
            $this->db_connect('ONE');
        }
        list($this->_table_one_columns,$this->_table_one_types) = $this->getColumns($this->_table_one_name);

        if(isset($this->_table_two_db_pass))
        {
            $this->db_connect('TWO');
        }
        list($this->_table_two_columns,$this->_table_two_types) = $this->getColumns($this->_table_two_name);

        $this->addAdditionalColumns($this->getAdditionalColumns());
    }

    function setTableOneConnection($host, $user, $pass, $name)
    {
        $this->_table_one_db_host = $host;
        $this->_table_one_db_user = $user;
        $this->_table_one_db_pass = $pass;
        $this->_table_one_db_name = $name;
    }

    function setTableTwoConnection($host, $user, $pass, $name)
    {
        $this->_table_two_db_host = $host;
        $this->_table_two_db_user = $user;
        $this->_table_two_db_pass = $pass;
        $this->_table_two_db_name = $name;
    }

    function db_connect($table)
    {
        switch(strtoupper($table))
        {
            case 'ONE':
                $host = $this->_table_one_db_host;
                $user = $this->_table_one_db_user;
                $pass = $this->_table_one_db_pass;
                $name = $this->_table_one_db_name;
                $link = $this->_table_one_link = mysql_connect($host, $user, $pass, true);
                mysql_select_db($name) or die(mysql_error());
            break;
            case 'TWO';
                $host = $this->_table_two_db_host;
                $user = $this->_table_two_db_user;
                $pass = $this->_table_two_db_pass;
                $name = $this->_table_two_db_name;
                $link = $this->_table_two_link = mysql_connect($host, $user, $pass, true);
                mysql_select_db($name) or die(mysql_error());
            break;
            default:
                die('Improper parameter in MatchTable->db_connect() expecting "one" or "two".');
            break;
        }
        if (!$link) {
            die('Could not connect: ' . mysql_error());
        }
    }

    function getColumns($table_name)
    {
        $columns = array();
</script>