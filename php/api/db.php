<?php

/* This class is responsible for connecting to the DB and has helper methods to retrieve data */
class DB
{
  const DB_EXTENSION = '.sqlite3';

  private $dbPath = null;

  // Connect to our DB
  private $db = null;

  function __construct(){
    // Set the DB path
    $this->dbPath = realpath(dirname(__FILE__)) . '/' . $GLOBALS['cfg']['databaseFile'] . self::DB_EXTENSION;

    // Ensure our DB exists. We're going to lazy assume that if the file exists, the tables are configured.
    if(!file_exists($this->dbPath)) {
      $this->createDatabase();
    }

    $this->db = new PDO('sqlite:' . $this->dbPath );

    // Set errormode to exceptions
    $this->db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
  }

  // Raw queries
  public function query($q){
    $result = $this->db->query($q);

    return $result->fetchAll(PDO::FETCH_ASSOC);
  }

  // Any weird stuff you wana do
  public function getDb(){
    return $this->db;
  }

  // Returns User ID for the user passed
  // If the user doesn't exist they are created
  public function getUserId($user) {
    $prepare = $this->db->prepare('SELECT Id FROM Employee WHERE Name = :Name');
    $prepare->bindValue(':Name', $user);

    if(!$prepare->execute()) {
      return null;
    }

    $results = $prepare->fetchAll(PDO::FETCH_ASSOC);

    // ooh we got one
    if(count($results) > 0) {
      return $results[0]['Id'];
    }

    // OK, let's create
    $prepare = $this->db->prepare('INSERT INTO Employee (Name) VALUES (:Name)');
    $prepare->bindValue(':Name', $user);

    if($prepare->execute()){
      return $this->db->lastInsertId();
    } else {
      return null;
    }
  }

  // Returns the Project ID for the passed project name
  // If the project does not exist, it is created
  public function getProjectId($project){
    $prepare = $this->db->prepare('SELECT Id FROM Project WHERE Code = :Code');
    $prepare->bindValue(':Code', $project);

    if(!$prepare->execute()){
      return null;
    }

    $results = $prepare->fetchAll(PDO::FETCH_ASSOC);

    // Did we get one?
    if(count($results) > 0){
      return $results[0]['Id'];
    }

    // Nope? Create it
    $prepare = $this->db->prepare('INSERT INTO Project (Code) Values (:Code)');
    $prepare->bindValue(':Code', $project);

    if($prepare->execute()){
      return $this->db->lastInsertId();
    } else {
      return null;
    }
  }

  public function updateDaysHours($employeeId, $projectId, $date, $hours){

    $prepare = $this->db->prepare('SELECT TimeEntry.Id FROM TimeEntry LEFT OUTER JOIN Project ON TimeEntry.ProjectId = Project.Id LEFT OUTER JOIN Employee ON TimeEntry.EmployeeId = Employee.Id WHERE Project.Id = :ProjectId AND Employee.Id = :EmployeeId AND TimeEntry.Date = :TimeEntryDate');

    $prepare->bindValue(':ProjectId', $projectId);
    $prepare->bindValue(':EmployeeId', $employeeId);
    $prepare->bindValue(':TimeEntryDate', $date);

    if(!$prepare->execute()){
      return null;
    }

    $results = $prepare->fetchAll(PDO::FETCH_ASSOC);

    // Did we get one?
    if(count($results) > 0){
      $timeEntryId = $results[0]['Id'];

      // Entry already exists, update it
      $prepare = $this->db->prepare('UPDATE TimeEntry SET Hours = :Hours WHERE ProjectId = :ProjectId AND EmployeeId = :EmployeeId AND Date = :TimeEntryDate');

      $prepare->bindValue(':Hours', $hours);
      $prepare->bindValue(':ProjectId', $projectId);
      $prepare->bindValue(':EmployeeId', $employeeId);
      $prepare->bindValue(':TimeEntryDate', $date);

      $prepare->execute();
    } else {
      // Create the entry
      $prepare = $this->db->prepare('INSERT INTO TimeEntry (ProjectId, EmployeeId, Date, Hours) VALUES (:ProjectId, :EmployeeId, :TimeEntryDate, :Hours)');

      $prepare->bindValue(':Hours', $hours);
      $prepare->bindValue(':ProjectId', $projectId);
      $prepare->bindValue(':EmployeeId', $employeeId);
      $prepare->bindValue(':TimeEntryDate', $date);

      $prepare->execute();
    }
  }

  public function getAllEmployees(){
    return $this->query('SELECT Id as id, Name as name, LastUpdated as lastUpdated FROM Employee WHERE 1');
  }

  public function getAllProjects(){
    return $this->query('SELECT Id as id, Code as code, Name as name, LastUpdated as lastUpdated  FROM Project WHERE 1');
  }

  public function getRecentProjects(){
    return $this->query('SELECT Id as id, Code as code, Name as name, LastUpdated as lastUpdated, (SELECT SUM(Hours) FROM TimeEntry WHERE ProjectId = Project.Id) as hours FROM Project WHERE 1 ORDER BY LastUpdated LIMIT 5');
  }

  public function getPotato(){

    $prepare = $this->db->prepare('SELECT P.Code as projectCode, E.Name as employeeName, SUBSTR(T.Date, 1, 4) as year,
                                   SUBSTR(T.Date, 6, 2) as month, SUBSTR(T.Date, 9, 2) as day, T.Hours as hours
                                   FROM TimeEntry as T LEFT JOIN Employee as E ON T.EmployeeId = E.Id
                                   LEFT JOIN Project as P ON T.ProjectId = P.Id WHERE
                                   P.Code = :code AND T.Hours > 0');
    $prepare->bindValue(':code', '07144.0114.OPT2.28FJ.00KK.CACL');

    $prepare->execute();

    $result = $prepare->fetchAll(PDO::FETCH_ASSOC);

    var_dump($result);

    /*

    "SELECT `Project`.`Code`, `Employee`.`Name`, `TimeEntry`.`Date`, `TimeEntry`.`Hours`
                    FROM `TimeEntry` LEFT JOIN `Employee` ON `TimeEntry`.`EmployeeId`=`Employee`.`Id`
                    LEFT JOIN `Project` ON `TimeEntry`.`ProjectId` = `Project`.`Id` WHERE
                    `Project`.`Code`= ? AND `TimeEntry`.`Hours` <> 0 ORDER BY `Employee`.`Name`, `TimeEntry`.`Date`";
                    */

  }

  public function getProjectData($projectCode){
    $prepare = $this->db->prepare('SELECT P.Code as projectCode, E.Name as employeeName, SUBSTR(T.Date, 1, 4) as year,
                                   SUBSTR(T.Date, 6, 2) as month, SUBSTR(T.Date, 9, 2) as day, T.Hours as hours
                                   FROM TimeEntry as T LEFT JOIN Employee as E ON T.EmployeeId = E.Id
                                   LEFT JOIN Project as P ON T.ProjectId = P.Id WHERE
                                   P.Code = :code AND T.Hours > 0');
    $prepare->bindValue(':code', $projectCode);

    $prepare->execute();

    $result = $prepare->fetchAll(PDO::FETCH_ASSOC);

    return $result;
  }

/***************
 DELETE BELOW ME
 ***************/
  public function getAllEntries($stoneId){
    $query = 'SELECT serial, slabCount, finishType, slabSize, (SELECT url FROM images WHERE images.id = stoneEntries.thumbId) as thumb, (SELECT url FROM images WHERE images.id = stoneEntries.imageId) as image FROM stoneEntries WHERE stoneId = :id';
    $prepare = $this->db->prepare($query);
    $prepare->bindValue(':id', $stoneId, SQLITE3_INTEGER);

    if(!$prepare->execute())
      return null;

    $entry = $prepare->fetchAll(PDO::FETCH_ASSOC);

    // Add image prefix
    foreach($entry as &$e){
      $e['image'] = $GLOBALS['cfg']['imagePrefix'] . $e['image'];
      $e['thumb'] = $GLOBALS['cfg']['imagePrefix'] . $e['thumb'];
    }

    return $entry;
  }

  public function getAllStones(){
    $result = $this->query('SELECT stones.id as id FROM stones');

    $allStones = array();

    // Iterate over each ID
    foreach($result as $r){
      $x = $this->getStoneById($r['id']);

      $allStones[] = $x;
    }

    return $allStones;
  }

  public function getStoneById($id){
    $query = 'SELECT stones.id as code, stones.name as name, materials.name as type, images.url as thumbnail, (SELECT count(stoneEntries.id) FROM stoneEntries WHERE stoneEntries.stoneId = stones.id) as quantity FROM stones INNER JOIN materials ON stones.materialId = materials.id INNER JOIN images ON stones.coverImageId = images.id WHERE stones.id = :id';
    $prepare = $this->db->prepare($query);
    $prepare->bindValue(':id', $id, SQLITE3_INTEGER);

    if(!$prepare->execute())
      return null;

    // This will contain the stone without colors
    $stone = $prepare->fetchObject();

    if($stone == false)
      return null;

    // Add the prefix to the thumbnail
    $stone->thumbnail = $GLOBALS['cfg']['imagePrefix'] . $stone->thumbnail;

    if(count($stone) < 1)
      return null;

    // now we get the colors
    $query = 'SELECT colors.name FROM colorMap INNER JOIN colors ON colorMap.colorId = colors.id WHERE colorMap.stoneId = :id';
    $prepare = $this->db->prepare($query);
    $prepare->bindValue(':id', $id, SQLITE3_INTEGER);

    if(!$prepare->execute())
      return null;

    $stone->colors = array_values($prepare->fetchAll(PDO::FETCH_COLUMN | PDO::FETCH_UNIQUE, 0));

    return $stone;
  }

  // Inserts a new image into the DB returning its ID
  public function createNewImage($url) {
    $prepare = $this->db->prepare('INSERT INTO images (url) VALUES (:url)');
    $prepare->bindValue(':url', $url);

    $prepare->execute();

    return $this->db->lastInsertId();
  }

  // Deletes the
  public function deleteStoneCoverImage($stoneId){

    $prepare = $this->db->prepare('SELECT coverImageId FROM stones WHERE id = :id');
    $prepare->bindValue(':id', $stoneId);
    $prepare->execute();

    $r = $prepare->fetchObject();

    $this->deleteImageId($r->coverImageId);
  }

  // Deletes the given image from the image table
  public function deleteImageId($id){

    // Get the file name
    $prepare = $this->db->prepare('SELECT url FROM images WHERE id = :id');
    $prepare->bindValue(':id', $id);
    if(!$prepare->execute()){
      return;
    }

    $i = $prepare->fetchObject();

    // Remove it from the db
    $prepare = $this->db->prepare('DELETE FROM images WHERE id = :id');
    $prepare->bindValue(':id', $id);
    if(!$prepare->execute()){
      return;
    }

    // physically delete the file
    $imgRoot = dirname(__FILE__).'/'.$GLOBALS['cfg']['imageDir'];
    $imgPath = realpath($imgRoot.'/'.$i->url);

    unlink($imgPath);
  }

  public function removeStoneEntry($entryId){

    // Cleanup images
    $prepare = $this->db->prepare('SELECT thumbId, imageId FROM stoneEntries WHERE id = :id');
    $prepare->bindValue(':id', $entryId);
    $prepare->execute();

    $i = $prepare->fetchObject();

    if(!empty($i->imageId))
      $this->deleteImageId($i->imageId);
    if(!empty($i->thumbId))
      $this->deleteImageId($i->thumbId);

    // Delete the entry
    $prepare = $this->db->prepare('DELETE FROM stoneEntries WHERE id = :id');

    $prepare->bindValue(':id', $entryId);
    $prepare->execute();

  }

  public function removeStone($stoneId){

    // Remove all entries first
    $prepare = $this->db->prepare('SELECT id FROM stoneEntries WHERE stoneId = :id');
    $prepare->bindValue(':id', $stoneId);
    $prepare->execute();

    $entries = $prepare->fetchAll(PDO::FETCH_ASSOC);

    foreach($entries as $e){
      $this->removeStoneEntry($e['id']);
    }

    // Cleanup image
    $prepare = $this->db->prepare('SELECT coverImageId FROM stones WHERE id = :id');
    $prepare->bindValue(':id', $stoneId);
    $prepare->execute();

    $i = $prepare->fetchObject();

    if(!empty($i->coverImageId))
      $this->deleteImageId($i->coverImageId);

    // Cleanup colors
    $prepare = $this->db->prepare('DELETE FROM colorMap WHERE stoneId = :id');
    $prepare->bindValue(':id', $stoneId);
    $prepare->execute();

    // Kill ourselves
    $prepare = $this->db->prepare('DELETE FROM stones WHERE id = :id');
    $prepare->bindValue(':id', $stoneId);
    $prepare->execute();

  }

  /***************
 STOP DELETING
 ***************/


  /*
    Creates our database table structure if it isn't already defined
  */
  private function createDatabase() {

    $tdb = new PDO('sqlite:' . $this->dbPath );

    // Create Tables
    $tables = array(
      "CREATE TABLE 'Employee' ('Id' INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, 'Name' TEXT, 'LastUpdated' DATETIME DEFAULT CURRENT_TIMESTAMP)",
      "CREATE TABLE 'Project' ('Id' INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, 'Code' TEXT, 'Name' TEXT, 'LastUpdated' DATETIME DEFAULT CURRENT_TIMESTAMP)",
      "CREATE TABLE 'TimeEntry' ('Id' INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, 'ProjectId' INTEGER NOT NULL, 'EmployeeId' INTEGER NOT NULL, 'Date' TEXT, 'Hours' TEXT, 'LastUpdated' DATETIME DEFAULT CURRENT_TIMESTAMP)"
    );

    foreach ($tables as $a) {
      $tdb->exec($a);
    }

    // Add Triggers
    $triggers = array(
      "CREATE TRIGGER EmployeeTimeUpdate AFTER UPDATE ON Employee BEGIN UPDATE Employee SET LastUpdated = datetime('now') WHERE Id = new.Id; END;",
      "CREATE TRIGGER ProjectTimeUpdate AFTER UPDATE ON Project BEGIN UPDATE Project SET LastUpdated = datetime('now') WHERE Id = new.Id; END;",
      "CREATE TRIGGER TimeEntryTimeUpdate AFTER UPDATE ON TimeEntry BEGIN UPDATE TimeEntry SET LastUpdated = datetime('now') WHERE Id = new.Id; END;"
    );

    foreach ($triggers as $a) {
      $tdb->exec($a);
    }

    $tdb = null;
  }

}



?>