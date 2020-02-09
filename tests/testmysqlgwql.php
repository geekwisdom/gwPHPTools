<?php
require_once __DIR__ . '/../../../autoload.php'; // Autoload files using
use \org\geekwisdom\GWException;
use \org\geekwisdom\GWDataTable;
use \org\geekwisdom\GWDataRow;
use \org\geekwisdom\GWDBConnection;
use \org\geekwisdom\GWQL;
use \org\geekwisdom\GWQLXPathBuilder;
use \org\geekwisdom\GWQLSqlStringBuilder;

//$COMPARESTRING="[ [ AGE _EQ_ 19 ] _OR_ [ NAME _EQ_ BRADR _OR_ NAME _LIKE_ DAVE ] ] _AND_ [ AGE _EQ_ 9 _OR_ AGE _LT_ 12 ]";
//$COMPARESTRING="[ AGE _GE_ 7 _AND_ AGE _LE_ 15 ]";
//$COMPARESTRING='[ [ Name _EQ_ "Mike Gold" _OR_  Name _EQ_ "Mahesh Chand" ] _AND_ [  Address _EQ_ "NewYork" ] ]';
//$COMPARESTRING='[ [ Name _EQ_ "Mike Gold" _OR_  Name _EQ_ "Mahesh Chand" ] _AND_ [  Address _EQ_ "NewYork" ] ]';
//$COMPARESTRING='[ [ Name _EQ_ "Mike Gold" ] _OR_  [ Name _EQ_ "Mahesh Chand"  _AND_  Address _EQ_ "NewYork" ] ]';

$COMPARESTRING_A='[ [ A _EQ_ "2" ] _AND_  [ B _EQ_ "3"  _OR_  C _EQ_ "1" ] ]';
$COMPARESTRING_B='[ [ A _EQ_ "2"  _AND_ B _EQ_ "3" ] _OR_  [ C _EQ_ "1" ] ]';

$f = fopen( 'php://stdin', 'r' );
$done = false;
while( !$done ) {
echo "Enter Query:";
$done = $line = fgets( $f );
if ($line == "QUIT") $done=true;
else
 { 
$connectstr=__DIR__ . "/../tests/testconn.dsn";
$PD = new GWDBConnection($connectstr);

$sqltester = new GWQL($line);
$mysqlbuilder = new GWQLSqlStringBuilder();
$allowedFields["ID"] = "UserID";
$sqltester->setAllowedFields($allowedFields);
$query = "SELECT * FROM sec_UsersTable WHERE ";
$finalCmd = $sqltester->getCommand($mysqlbuilder);

if ($sqltester->getFlag("locked") == true) $stmt=$PD->prepare($query . " locked = true AND " . $finalCmd);
else $stmt=$PD->prepare($query . $finalCmd);
$p=$mysqlbuilder->getParams();
                $cnt=1;
		print_r($p);
              foreach ($p as $key => $val)
                for ($i=0;$i<count($p);$i++)
                        {
                        $stmt->bindParam($cnt,$p[$i]);
                        $cnt++;
                        }
                $stmt->execute();
                $rows=$stmt->fetchAll(PDO::FETCH_ASSOC);
		print_r($rows);
}
}

?>
